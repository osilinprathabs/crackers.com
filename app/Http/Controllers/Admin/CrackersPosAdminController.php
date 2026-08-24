<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrackersProduct;
use App\Models\CrackersCategory;
use App\Models\CrackersOrder;
use App\Models\CrackersOrderItem;
use App\Models\CrackersSetting;
use App\Models\CrackersInventoryLog;
use App\Models\Account\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CrackersPosAdminController extends Controller
{
    /**
     * Display POS Counter Interface
     */
    public function index(Request $request)
    {
        $categories = CrackersCategory::where('status', true)
            ->orderBy('name', 'asc')
            ->get();

        $products = CrackersProduct::where('status', true)
            ->orderBy('name', 'asc')
            ->get();

        $settings = CrackersSetting::getSettings();

        $customers = Customer::select('id', 'company_name', 'contact_person_name', 'contact_person_mobile')
            ->orderBy('contact_person_name', 'asc')
            ->take(200)
            ->get();

        return view('admin.pos.index', compact('categories', 'products', 'settings', 'customers'));
    }

    /**
     * Store POS Counter Transaction & Decrement Stock
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_type' => 'required|in:walkin,existing,new',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|string',
            'amount_tendered' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|string|in:amount,percent',
            'discount_value' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:crackers_products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Enforce Mandatory Customer Selection
        if ($validated['customer_type'] === 'existing' && empty($validated['customer_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Customer Selection Mandatory: Please select an existing customer from dropdown.',
            ], 422);
        }

        if (($validated['customer_type'] === 'new' || $validated['customer_type'] === 'walkin') && (empty($validated['customer_name']) || empty($validated['customer_phone']))) {
            return response()->json([
                'success' => false,
                'message' => 'Customer Selection Mandatory: Customer Name and Mobile Number are required.',
            ], 422);
        }

        try {
            return DB::transaction(function() use ($request, $validated) {
                $settings = CrackersSetting::getSettings();

                // 1. Customer Handling
                $customerId = null;
                $customerName = trim($validated['customer_name'] ?? '');
                $customerPhone = trim($validated['customer_phone'] ?? '');
                $customerEmail = trim($validated['customer_email'] ?? '');

                if ($validated['customer_type'] === 'existing' && !empty($validated['customer_id'])) {
                    $customer = Customer::findOrFail($validated['customer_id']);
                    $customerId = $customer->id;
                    $customerName = $customer->contact_person_name ?: $customer->company_name;
                    $customerPhone = $customer->contact_person_mobile ?: 'N/A';
                    $customerEmail = $customer->contact_person_email ?? null;
                } elseif ($validated['customer_type'] === 'new' && !empty($validated['customer_name'])) {
                    $customer = new Customer();
                    $customer->customer_code = Customer::generateCustomerCode();
                    $customer->company_name = $validated['customer_name'];
                    $customer->contact_person_name = $validated['customer_name'];
                    $customer->contact_person_mobile = $validated['customer_phone'] ?: '9999999999';
                    $customer->contact_person_email = $validated['customer_email'] ?? '';
                    $customer->save();

                    $customerId = $customer->id;
                    $customerName = $customer->contact_person_name;
                    $customerPhone = $customer->contact_person_mobile;
                    $customerEmail = $customer->contact_person_email;
                } elseif (!empty($validated['customer_name'])) {
                    $customerName = $validated['customer_name'];
                    if (!empty($validated['customer_phone'])) {
                        $customerPhone = $validated['customer_phone'];
                    }
                }

                // 2. Validate Stock & Calculate Items Subtotal
                $subtotal = 0;
                $orderItemsData = [];

                foreach ($validated['items'] as $itemData) {
                    $product = CrackersProduct::where('id', $itemData['id'])->lockForUpdate()->firstOrFail();

                    if ($product->stock < $itemData['quantity']) {
                        throw new \Exception("Stock error for '{$product->name}'. Available stock: {$product->stock}. Requested: {$itemData['quantity']}.");
                    }

                    $unitPrice = $product->discount_price ?: $product->price;
                    $lineTotal = round($unitPrice * $itemData['quantity'], 2);
                    $subtotal += $lineTotal;

                    $orderItemsData[] = [
                        'product' => $product,
                        'unit_price' => $unitPrice,
                        'quantity' => $itemData['quantity'],
                        'total_price' => $lineTotal,
                    ];
                }

                // Calculate Discount (Amount or Percentage)
                $discountType = $validated['discount_type'] ?? 'amount';
                $discountVal = floatval($validated['discount_value'] ?? $validated['discount'] ?? 0);

                if ($discountType === 'percent') {
                    $calculatedDiscount = round(($subtotal * $discountVal) / 100, 2);
                } else {
                    $calculatedDiscount = floatval($discountVal);
                }

                $discount = min($subtotal, max(0, $calculatedDiscount));

                // 3. Tax & Total Calculations
                $taxableSubtotal = max(0, $subtotal - $discount);
                $gstRate = floatval($settings->gst_percentage ?: 0);
                $gstAmount = round($taxableSubtotal * ($gstRate / 100), 2);
                $grandTotal = round($taxableSubtotal + $gstAmount, 2);

                $amountTendered = floatval($validated['amount_tendered'] ?? 0);
                $changeAmount = ($amountTendered > $grandTotal) ? round($amountTendered - $grandTotal, 2) : 0.00;

                // 4. Create Order Record
                $order = CrackersOrder::create([
                    'order_number' => 'CRK-POS-' . date('YmdHis') . '-' . rand(100, 999),
                    'customer_id' => $customerId,
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'customer_email' => $customerEmail,
                    'delivery_address' => 'Over-the-counter (POS Store)',
                    'city' => 'In-Store',
                    'pincode' => '000000',
                    'subtotal' => $subtotal,
                    'gst_rate' => $gstRate,
                    'gst_amount' => $gstAmount,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'paid',
                    'status' => 'delivered',
                    'notes' => $validated['notes'] ?? 'Counter POS Sale (Walk-in)',
                ]);

                // 5. Save Items, Update Product Stock & Log Stock Deduction
                foreach ($orderItemsData as $data) {
                    /** @var CrackersProduct $product */
                    $product = $data['product'];
                    $qty = $data['quantity'];
                    $prevStock = $product->stock;

                    // Create Order Item
                    CrackersOrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $data['unit_price'],
                        'quantity' => $qty,
                        'total_price' => $data['total_price'],
                    ]);

                    // Decrement Product Stock
                    $newStock = max(0, $prevStock - $qty);
                    $product->stock = $newStock;
                    $product->save();

                    // Record Inventory Log
                    CrackersInventoryLog::create([
                        'product_id' => $product->id,
                        'type' => 'pos_sale',
                        'quantity' => -$qty,
                        'old_stock' => $prevStock,
                        'new_stock' => $newStock,
                        'notes' => "POS Counter Sale Order #{$order->order_number}",
                        'created_by' => Auth::check() ? Auth::user()->name : 'POS Counter',
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'POS Sale Completed Successfully!',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'grand_total' => $grandTotal,
                    'amount_tendered' => $amountTendered,
                    'change_amount' => $changeAmount,
                    'receipt_url' => route('admin.pos.receipt', $order->id),
                ]);
            });
        } catch (\Exception $e) {
            Log::error("POS Error: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'An error occurred while processing the POS sale.',
            ], 422);
        }
    }

    /**
     * Render Printable 80mm Thermal Receipt
     */
    public function receipt($id)
    {
        $order = CrackersOrder::with(['items', 'customer'])->findOrFail($id);
        $settings = CrackersSetting::getSettings();

        return view('admin.pos.receipt', compact('order', 'settings'));
    }

    /**
     * Store POS Quotation / Price Estimate (Without Stock Deduction)
     */
    public function storeQuotation(Request $request)
    {
        $validated = $request->validate([
            'customer_type' => 'required|in:walkin,existing,new',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'discount_type' => 'nullable|string|in:amount,percent',
            'discount_value' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:crackers_products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Enforce Mandatory Customer Selection
        if ($validated['customer_type'] === 'existing' && empty($validated['customer_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Customer Selection Mandatory: Please select an existing customer from dropdown.',
            ], 422);
        }

        if (($validated['customer_type'] === 'new' || $validated['customer_type'] === 'walkin') && (empty($validated['customer_name']) || empty($validated['customer_phone']))) {
            return response()->json([
                'success' => false,
                'message' => 'Customer Selection Mandatory: Customer Name and Mobile Number are required.',
            ], 422);
        }

        try {
            return DB::transaction(function() use ($validated) {
                $settings = CrackersSetting::getSettings();

                // 1. Customer Details
                $customerId = null;
                $customerName = trim($validated['customer_name'] ?? '');
                $customerPhone = trim($validated['customer_phone'] ?? '');
                $customerEmail = trim($validated['customer_email'] ?? '');

                if ($validated['customer_type'] === 'existing' && !empty($validated['customer_id'])) {
                    $customer = Customer::findOrFail($validated['customer_id']);
                    $customerId = $customer->id;
                    $customerName = $customer->contact_person_name ?: $customer->company_name;
                    $customerPhone = $customer->contact_person_mobile ?: '';
                    $customerEmail = $customer->contact_person_email ?? null;
                } elseif ($validated['customer_type'] === 'new' && !empty($validated['customer_name'])) {
                    $customerName = $validated['customer_name'];
                    $customerPhone = $validated['customer_phone'] ?? '';
                    $customerEmail = $validated['customer_email'] ?? null;
                } elseif (!empty($validated['customer_name'])) {
                    $customerName = $validated['customer_name'];
                    if (!empty($validated['customer_phone'])) {
                        $customerPhone = $validated['customer_phone'];
                    }
                } elseif (!empty($validated['customer_phone'])) {
                    $customerPhone = $validated['customer_phone'];
                }

                // 2. Calculate Items Subtotal
                $subtotal = 0;
                $orderItemsData = [];

                foreach ($validated['items'] as $itemData) {
                    $product = CrackersProduct::findOrFail($itemData['id']);
                    $unitPrice = $product->discount_price ?: $product->price;
                    $lineTotal = round($unitPrice * $itemData['quantity'], 2);
                    $subtotal += $lineTotal;

                    $orderItemsData[] = [
                        'product' => $product,
                        'unit_price' => $unitPrice,
                        'quantity' => $itemData['quantity'],
                        'total_price' => $lineTotal,
                    ];
                }

                // Calculate Discount
                $discountType = $validated['discount_type'] ?? 'amount';
                $discountVal = floatval($validated['discount_value'] ?? $validated['discount'] ?? 0);

                if ($discountType === 'percent') {
                    $calculatedDiscount = round(($subtotal * $discountVal) / 100, 2);
                } else {
                    $calculatedDiscount = floatval($discountVal);
                }

                $discount = min($subtotal, max(0, $calculatedDiscount));

                // 3. Tax & Total Calculations
                $taxableSubtotal = max(0, $subtotal - $discount);
                $gstRate = floatval($settings->gst_percentage ?: 0);
                $gstAmount = round($taxableSubtotal * ($gstRate / 100), 2);
                $grandTotal = round($taxableSubtotal + $gstAmount, 2);

                // 4. Save Quotation Record
                $quotationNumber = 'QUO-' . date('YmdHis') . '-' . rand(100, 999);
                $order = CrackersOrder::create([
                    'order_number' => $quotationNumber,
                    'customer_id' => $customerId,
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'customer_email' => $customerEmail,
                    'delivery_address' => 'POS Price Quotation / Estimate',
                    'city' => 'In-Store Quote',
                    'pincode' => '000000',
                    'subtotal' => $subtotal,
                    'gst_rate' => $gstRate,
                    'gst_amount' => $gstAmount,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    'payment_method' => 'Quotation',
                    'payment_status' => 'pending',
                    'status' => 'quotation',
                    'notes' => $validated['notes'] ?? 'POS Price Estimate / Quotation',
                ]);

                // 5. Save Items (Without stock deduction)
                foreach ($orderItemsData as $data) {
                    CrackersOrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $data['product']->id,
                        'product_name' => $data['product']->name,
                        'unit_price' => $data['unit_price'],
                        'quantity' => $data['quantity'],
                        'total_price' => $data['total_price'],
                    ]);
                }

                $quotationPublicUrl = route('public.pos.quotation.view', $order->id);

                // Build WhatsApp message text (includes item breakdown & direct PDF link)
                $waText = "📜 *OFFICIAL QUOTATION / PRICE ESTIMATE*\n";
                $waText .= "🏢 *" . ($settings->company_name ?: 'S.R. TRADERS') . "*\n";
                $waText .= "Quotation No: *" . $order->order_number . "*\n";
                $waText .= "Date: " . date('d M Y, h:i A') . "\n";
                if ($customerName) $waText .= "Customer: *" . $customerName . "*\n";
                $waText .= "----------------------------------\n";
                $waText .= "*ESTIMATED ITEMS:*\n";
                foreach ($orderItemsData as $idx => $d) {
                    $waText .= ($idx + 1) . ". " . $d['product']->name . "\n   " . $d['quantity'] . " " . $d['product']->unit . " x ₹" . number_format($d['unit_price'], 2) . " = *₹" . number_format($d['total_price'], 2) . "*\n";
                }
                $waText .= "----------------------------------\n";
                $waText .= "Subtotal: ₹" . number_format($subtotal, 2) . "\n";
                if ($discount > 0) $waText .= "Discount: -₹" . number_format($discount, 2) . "\n";
                if ($gstAmount > 0) $waText .= "GST Tax ({$gstRate}%): ₹" . number_format($gstAmount, 2) . "\n";
                $waText .= "👉 *GRAND TOTAL: ₹" . number_format($grandTotal, 2) . "*\n";
                $waText .= "----------------------------------\n";
                $waText .= "📄 *View / Download Quotation PDF:*\n";
                $waText .= $quotationPublicUrl . "\n";
                $waText .= "----------------------------------\n";
                $waText .= "Thank you for inquiring with us! Reply to this message to confirm your order.";

                return response()->json([
                    'success' => true,
                    'message' => 'POS Quotation Created Successfully!',
                    'quotation_id' => $order->id,
                    'quotation_number' => $order->order_number,
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'grand_total' => $grandTotal,
                    'subtotal' => $subtotal,
                    'gst_amount' => $gstAmount,
                    'discount' => $discount,
                    'quotation_url' => $quotationPublicUrl,
                    'whatsapp_text' => $waText,
                ]);
            });
        } catch (\Exception $e) {
            Log::error("POS Quotation Error: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'An error occurred while creating POS quotation.',
            ], 422);
        }
    }

    /**
     * Render Printable POS Quotation / PDF View
     */
    public function quotationView($id)
    {
        $order = CrackersOrder::with(['items', 'customer'])->findOrFail($id);
        $settings = CrackersSetting::getSettings();

        return view('admin.pos.quotation', compact('order', 'settings'));
    }
}
