<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrackersProduct;
use App\Models\CrackersOrder;
use App\Models\CrackersOrderItem;
use App\Models\CrackersSetting;

use App\Models\CrackersCategory;

use App\Models\CrackersInventoryLog;
use App\Models\Account\Customer;
use App\Models\CrackersBankAccount;
use Illuminate\Support\Facades\DB;

class CrackersStoreController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $query = CrackersProduct::where('status', true);

        if (!empty($category) && $category !== 'All') {
            $query->where('category', $category);
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $customerType = 'retail';
        if ($request->has('type')) {
            $customerType = $request->query('type') === 'wholesale' ? 'wholesale' : 'retail';
            session(['store_mode' => $customerType, 'customer_type' => $customerType]);
        } elseif (session()->has('store_mode')) {
            $customerType = session('store_mode');
        } elseif (session()->has('customer_type')) {
            $customerType = session('customer_type');
        } elseif (auth()->check()) {
            $cust = Customer::where('user_id', auth()->id())->first();
            if ($cust && !empty($cust->customer_type)) {
                $customerType = $cust->customer_type;
                session(['store_mode' => $customerType, 'customer_type' => $customerType]);
            }
        }

        $products = $query->orderBy('is_featured', 'desc')->latest()->get();
        $dbCategories = CrackersCategory::where('status', true)->pluck('name')->toArray();
        $categories = array_merge(['All'], $dbCategories);
        $featuredProducts = CrackersProduct::where('status', true)->where('is_featured', true)->take(4)->get();
        $settings = CrackersSetting::getSettings();
        $heroBanners = \App\Models\Slide::where('type', 'banner')->latest()->get();
        $appearance = \App\Models\Appearance::where('type', 'web')->first();
        $companyDetail = \App\Models\CompanyDetail::first();

        return view('crackers.index', compact('products', 'categories', 'featuredProducts', 'category', 'search', 'settings', 'customerType', 'heroBanners', 'appearance', 'companyDetail'));
    }

    public function checkout(Request $request)
    {
        $settings = CrackersSetting::getSettings();
        $activeBanks = CrackersBankAccount::where('is_active', true)->get();
        $user = auth()->user();
        $customer = null;
        if ($user) {
            $customer = \App\Models\Account\Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                $customer = \App\Models\Account\Customer::where('contact_person_mobile', $user->phone)->first();
            }
        }
        $customerType = session('store_mode', session('customer_type', 'retail'));
        if ($user && $customer && !empty($customer->customer_type) && !session()->has('store_mode')) {
            $customerType = $customer->customer_type;
        }

        $appearance = \App\Models\Appearance::where('type', 'web')->first();
        $websiteColor = $appearance?->data['header_color'] ?? '#fb8500';

        return view('crackers.checkout', compact('settings', 'activeBanks', 'user', 'customer', 'appearance', 'websiteColor', 'customerType'));
    }

    public function showPolicy($type)
    {
        $settings = CrackersSetting::getSettings();
        
        $policyMap = [
            'terms' => ['title' => 'Terms & Conditions', 'field' => 'terms_and_conditions'],
            'terms_and_conditions' => ['title' => 'Terms & Conditions', 'field' => 'terms_and_conditions'],
            'privacy' => ['title' => 'Privacy Policy', 'field' => 'privacy_policy'],
            'privacy_policy' => ['title' => 'Privacy Policy', 'field' => 'privacy_policy'],
            'shipping' => ['title' => 'Shipping & Delivery Policy', 'field' => 'shipping_policy'],
            'shipping_policy' => ['title' => 'Shipping & Delivery Policy', 'field' => 'shipping_policy'],
        ];

        if (!array_key_exists($type, $policyMap)) {
            abort(404);
        }

        $title = $policyMap[$type]['title'];
        $field = $policyMap[$type]['field'];
        $content = $settings->{$field} ?: 'No content available for this policy.';

        return view('crackers.policy', compact('title', 'content', 'type'));
    }

    public function placeOrder(Request $request)
    {
        if ($request->has('items_json') && is_string($request->input('items_json'))) {
            $decoded = json_decode($request->input('items_json'), true);
            if (is_array($decoded)) {
                $request->merge(['items' => $decoded]);
            }
        }

        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_email' => 'nullable|email|max:255',
                'delivery_address' => 'required|string',
                'city' => 'nullable|string|max:100',
                'pincode' => 'nullable|string|max:20',
                'payment_method' => 'nullable|string',
                'notes' => 'nullable|string',
                'payment_proof' => 'nullable|image|max:5120',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:crackers_products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            $firstError = collect($ve->errors())->flatten()->first();
            return response()->json([
                'success' => false,
                'message' => $firstError ?: 'Please fill out all required checkout fields.',
                'errors' => $ve->errors()
            ], 422);
        }

        $paymentProofPath = null;
        $paymentMethod = $validated['payment_method'] ?? 'COD';
        if ($paymentMethod !== 'COD' && !$request->hasFile('payment_proof')) {
            return response()->json([
                'success' => false,
                'message' => 'Payment proof screenshot is mandatory. Order will only be taken after uploading payment receipt image!',
                'errors' => ['payment_proof' => ['Payment proof image is mandatory.']]
            ], 422);
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/payment_proofs'), $fileName);
            $paymentProofPath = 'uploads/payment_proofs/' . $fileName;
        }

        try {
            return DB::transaction(function() use ($validated, $paymentProofPath) {
                $settings = CrackersSetting::getSettings();

                $orderType = session('customer_type', 'retail');

                // Validate stock levels & Wholesale min qty before proceeding
                foreach ($validated['items'] as $itemData) {
                    $product = CrackersProduct::where('id', $itemData['id'])->lockForUpdate()->first();
                    if (!$product) {
                        throw new \Exception("One of the selected products no longer exists.");
                    }
                    if ($product->stock < $itemData['quantity']) {
                        throw new \Exception("Insufficient stock for '{$product->name}'. Only {$product->stock} {$product->unit}(s) available.");
                    }
                    if ($orderType === 'wholesale' && !empty($product->wholesale_min_qty) && $itemData['quantity'] < $product->wholesale_min_qty) {
                        throw new \Exception("Wholesale minimum order requirement for '{$product->name}' is {$product->wholesale_min_qty} {$product->unit}(s). You ordered {$itemData['quantity']}.");
                    }
                }

                // Find or create customer
                $customer = Customer::where('contact_person_mobile', $validated['customer_phone'])->first();
                if (!$customer) {
                    $customer = new Customer();
                    $customer->customer_code = Customer::generateCustomerCode();
                    $customer->company_name = $validated['customer_name'];
                    $customer->contact_person_name = $validated['customer_name'];
                    $customer->contact_person_mobile = $validated['customer_phone'];
                    $customer->contact_person_email = $validated['customer_email'] ?? '';
                    $customer->billing_address = [
                        'address' => $validated['delivery_address'],
                        'city' => $validated['city'] ?? '',
                        'pincode' => $validated['pincode'] ?? '',
                    ];
                    $customer->save();
                }

                // Calculate Order Totals & Dynamic GST
                $subtotal = 0;
                $orderItemsData = [];
                $productUpdates = [];

                foreach ($validated['items'] as $itemData) {
                    $product = CrackersProduct::findOrFail($itemData['id']);
                    $unitPrice = $product->discount_price ?: $product->price;
                    $lineTotal = $unitPrice * $itemData['quantity'];
                    $subtotal += $lineTotal;

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $unitPrice,
                        'quantity' => $itemData['quantity'],
                        'total_price' => $lineTotal,
                    ];

                    $productUpdates[] = [
                        'product' => $product,
                        'quantity' => $itemData['quantity']
                    ];
                }

                $gstRate = $settings->gst_percentage ?: 0;
                $gstAmount = round($subtotal * ($gstRate / 100), 2);
                $grandTotal = $subtotal + $gstAmount;

                // Enforce Minimum Order Amount Rules
                $minRetail = floatval($settings->min_retail_order_amount ?? 0);
                $minWholesale = floatval($settings->min_wholesale_order_amount ?? 0);
                $orderType = session('customer_type', 'retail');

                if ($orderType === 'wholesale' && $minWholesale > 0 && $subtotal < $minWholesale) {
                    throw new \Exception("Minimum order amount for Wholesale Bulk orders is ₹" . number_format($minWholesale, 2) . ". Your current subtotal is ₹" . number_format($subtotal, 2) . ".");
                }

                if ($orderType === 'retail' && $minRetail > 0 && $subtotal < $minRetail) {
                    throw new \Exception("Minimum order amount for Retail orders is ₹" . number_format($minRetail, 2) . ". Your current subtotal is ₹" . number_format($subtotal, 2) . ".");
                }

                $order = CrackersOrder::create([
                    'order_number' => CrackersOrder::generateOrderNumber(),
                    'customer_id' => $customer->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_email' => $validated['customer_email'] ?? null,
                    'delivery_address' => $validated['delivery_address'],
                    'city' => $validated['city'] ?? '',
                    'pincode' => $validated['pincode'] ?? '',
                    'subtotal' => $subtotal,
                    'gst_rate' => $gstRate,
                    'gst_amount' => $gstAmount,
                    'discount' => 0,
                    'grand_total' => $grandTotal,
                    'payment_method' => $validated['payment_method'] ?? 'COD',
                    'payment_proof' => $paymentProofPath,
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($orderItemsData as $item) {
                    $item['order_id'] = $order->id;
                    CrackersOrderItem::create($item);
                }

                // Deduct inventory & record audit log
                foreach ($productUpdates as $update) {
                    $p = $update['product'];
                    $qtyDeducted = $update['quantity'];
                    $oldStock = $p->stock;
                    $newStock = max(0, $oldStock - $qtyDeducted);

                    $p->stock = $newStock;
                    $p->save();

                    CrackersInventoryLog::create([
                        'product_id' => $p->id,
                        'type' => 'order_deduction',
                        'quantity' => $qtyDeducted,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'notes' => "Deducted for Customer Order #{$order->order_number}",
                        'created_by' => 'Store Front Order',
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'order_number' => $order->order_number,
                    'redirect_url' => route('crackers.order-success', $order->order_number),
                ]);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Order Placement Exception: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'An error occurred while placing your order.'
            ], 422);
        }
    }

    public function orderSuccess($orderNumber)
    {
        $order = CrackersOrder::with('items')->where('order_number', $orderNumber)->firstOrFail();
        $settings = CrackersSetting::getSettings();
        $activeBanks = CrackersBankAccount::where('is_active', true)->get();
        return view('crackers.success', compact('order', 'settings', 'activeBanks'));
    }

    public function downloadInvoice($orderNumber)
    {
        $order = CrackersOrder::with('items')->where('order_number', $orderNumber)->firstOrFail();
        $settings = CrackersSetting::getSettings();
        $activeBanks = CrackersBankAccount::where('is_active', true)->get();
        return view('crackers.invoice', compact('order', 'settings', 'activeBanks'));
    }

    public function uploadPaymentProof(Request $request, $orderNumber)
    {
        $order = CrackersOrder::where('order_number', $orderNumber)->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'proof_' . $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/payment_proofs');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $order->payment_proof = 'uploads/payment_proofs/' . $filename;
            $order->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment proof / screenshot uploaded successfully!',
                'proof_url' => asset($order->payment_proof),
            ]);
        }

        return redirect()->back()->with('success', 'Payment proof screenshot uploaded successfully!');
    }
}
