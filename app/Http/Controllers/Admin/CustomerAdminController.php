<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account\Customer;
use App\Models\User;
use App\Models\CrackersOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type', 'all');

        $query = Customer::with(['user', 'crackersOrders'])
            ->where(function($q) {
                $q->whereNull('user_id')
                  ->orWhereDoesntHave('user', function($uq) {
                      $uq->whereHas('roles', function($rq) {
                          $rq->whereIn('name', ['Admin', 'Agent', 'Staff', 'Super Admin']);
                      });
                  });
            });

        if ($type === 'wholesale') {
            $query->where('customer_type', 'wholesale');
        } elseif ($type === 'retail') {
            $query->where(function($q) {
                $q->whereNull('customer_type')
                  ->orWhere('customer_type', '!=', 'wholesale');
            });
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_code', 'like', '%' . $search . '%')
                  ->orWhere('contact_person_name', 'like', '%' . $search . '%')
                  ->orWhere('contact_person_mobile', 'like', '%' . $search . '%')
                  ->orWhere('contact_person_email', 'like', '%' . $search . '%');
            });
        }

        // KPI Counts & Revenue Calculations
        $allBaseCustomers = Customer::all();
        $totalCustomersCount = $allBaseCustomers->count();
        $wholesaleCount = $allBaseCustomers->where('customer_type', 'wholesale')->count();
        $retailCount = $totalCustomersCount - $wholesaleCount;
        $totalRevenue = CrackersOrder::where('payment_status', 'paid')->sum('grand_total');

        $customers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact(
            'customers', 
            'search', 
            'type', 
            'totalCustomersCount', 
            'wholesaleCount', 
            'retailCount', 
            'totalRevenue'
        ));
    }

    public function show($id)
    {
        $customer = Customer::with('user')->findOrFail($id);

        $orders = CrackersOrder::with('items')
            ->where(function($q) use ($customer) {
                if ($customer->id) {
                    $q->where('customer_id', $customer->id);
                }
                if ($customer->contact_person_mobile) {
                    $q->orWhere('customer_phone', $customer->contact_person_mobile);
                }
                if ($customer->contact_person_email) {
                    $q->orWhere('customer_email', $customer->contact_person_email);
                }
            })
            ->latest()
            ->get();

        $totalSpent = $orders->sum('grand_total');
        $totalOrdersCount = $orders->count();
        $posOrdersCount = $orders->filter(fn($o) => $o->is_pos)->count();
        $onlineOrdersCount = $totalOrdersCount - $posOrdersCount;

        return view('admin.customers.show', compact(
            'customer', 
            'orders', 
            'totalSpent', 
            'totalOrdersCount', 
            'posOrdersCount', 
            'onlineOrdersCount'
        ));
    }

    public function loginAsCustomer($id)
    {
        $adminId = Auth::id();
        $customer = Customer::findOrFail($id);

        $user = null;
        if ($customer->user_id) {
            $user = User::find($customer->user_id);
        }

        if (!$user && $customer->contact_person_mobile) {
            $user = User::where('phone', $customer->contact_person_mobile)->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $customer->contact_person_name ?: $customer->company_name,
                'phone' => $customer->contact_person_mobile ?: '9999999999',
                'email' => $customer->contact_person_email ?: null,
                'password' => Hash::make(Str::random(12)),
            ]);
            $customer->user_id = $user->id;
            $customer->save();
        }

        Auth::login($user);
        session(['impersonator_admin_id' => $adminId]);

        return redirect()->route('crackers.my-orders')->with('success', "Logged in as customer: {$user->name}");
    }

    public function stopImpersonating()
    {
        if (session()->has('impersonator_admin_id')) {
            $adminId = session('impersonator_admin_id');
            session()->forget('impersonator_admin_id');
            $adminUser = User::find($adminId);
            if ($adminUser) {
                Auth::login($adminUser);
                return redirect()->route('admin.customers.index')->with('success', 'Returned to Admin Panel.');
            }
        }

        return redirect()->route('admin.customers.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,contact_person_mobile',
            'email' => 'nullable|email|max:255',
            'customer_type' => 'nullable|string|in:retail,wholesale',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string',
        ]);

        $customer = new Customer();
        $customer->customer_code = Customer::generateCustomerCode();
        $customer->company_name = $validated['name'];
        $customer->contact_person_name = $validated['name'];
        $customer->contact_person_mobile = $validated['phone'];
        $customer->contact_person_email = $validated['email'] ?? '';
        $customer->customer_type = $validated['customer_type'] ?? 'retail';
        $customer->tax_number = $validated['tax_number'] ?? null;
        $customer->billing_address = ['address' => $validated['address'] ?? ''];
        $customer->shipping_address = ['address' => $validated['address'] ?? ''];
        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'CRM Customer profile created successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
