<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account\Customer;
use App\Models\CrackersOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerStoreAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Admin', 'Agent', 'Staff', 'Super Admin'])) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('crackers.my-orders');
        }
        return view('crackers.auth.login');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('crackers.my-orders');
        }
        return view('crackers.auth.register');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $validated['login'];
        $user = User::where('email', $login)->orWhere('phone', $login)->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid phone/email or password.'
                ], 401);
            }
            return redirect()->back()->with('error', 'Invalid phone/email or password.');
        }

        Auth::login($user, true);

        $isAdmin = method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Admin', 'Agent', 'Staff', 'Super Admin']);
        $redirectUrl = $isAdmin ? route('dashboard') : route('crackers.my-orders');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $isAdmin ? 'Admin logged in successfully!' : 'Logged in successfully!',
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
        ]);

        $passwordToSet = !empty($validated['password']) ? $validated['password'] : $validated['phone'];

        return DB::transaction(function() use ($validated, $passwordToSet, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'password' => Hash::make($passwordToSet),
                'plain_password' => $passwordToSet,
                'status' => 'active',
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
                'customer_code' => Customer::generateCustomerCode(),
                'company_name' => $validated['name'],
                'contact_person_name' => $validated['name'],
                'contact_person_mobile' => $validated['phone'],
                'contact_person_email' => $validated['email'] ?? null,
                'billing_address' => [
                    'address' => $validated['address'] ?? '',
                    'city' => $validated['city'] ?? '',
                    'pincode' => $validated['pincode'] ?? '',
                ],
            ]);

            Auth::login($user, true);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully!',
                    'redirect_url' => route('crackers.my-orders'),
                ]);
            }

            return redirect()->route('crackers.my-orders')->with('success', 'Account created successfully!');
        });
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('crackers.storefront');
    }

    public function myOrders()
    {
        if (!Auth::check()) {
            return redirect()->route('crackers.login-page')->with('error', 'Please login to view your orders.');
        }

        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        $orders = CrackersOrder::with('items')
            ->where(function($q) use ($user) {
                $q->where('customer_phone', $user->phone);
                if ($user->email) {
                    $q->orWhere('customer_email', $user->email);
                }
            })
            ->latest()
            ->paginate(10);

        return view('crackers.my_orders', compact('orders', 'user', 'customer'));
    }

    public function showProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('crackers.login-page')->with('error', 'Please login to view your profile.');
        }

        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        if (!$customer) {
            $customer = Customer::where('contact_person_mobile', $user->phone)->first();
        }

        $totalOrdersCount = \App\Models\CrackersOrder::where(function($q) use ($user) {
            $q->where('customer_phone', $user->phone);
            if ($user->email) {
                $q->orWhere('customer_email', $user->email);
            }
        })->count();

        $recentOrders = \App\Models\CrackersOrder::where(function($q) use ($user) {
            $q->where('customer_phone', $user->phone);
            if ($user->email) {
                $q->orWhere('customer_email', $user->email);
            }
        })->latest()->take(3)->get();

        return view('crackers.profile', compact('user', 'customer', 'totalOrdersCount', 'recentOrders'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('crackers.login-page');
        }

        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'new_password' => 'nullable|string|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'] ?? null;
        $user->phone = $validated['phone'];
        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
            $user->plain_password = $validated['new_password'];
        }
        $user->save();

        $customer = Customer::where('user_id', $user->id)->first();
        if (!$customer) {
            $customer = Customer::where('contact_person_mobile', $user->phone)->first();
        }

        if (!$customer) {
            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer->customer_code = Customer::generateCustomerCode();
        }

        $customer->company_name = $validated['name'];
        $customer->contact_person_name = $validated['name'];
        $customer->contact_person_mobile = $validated['phone'];
        $customer->contact_person_email = $validated['email'] ?? null;
        $customer->billing_address = [
            'address' => $validated['address'] ?? '',
            'city' => $validated['city'] ?? '',
            'pincode' => $validated['pincode'] ?? '',
        ];
        $customer->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile and address updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Profile and address updated successfully!');
    }
}
