<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrackersOrder;

class CrackersOrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $orderType = $request->query('order_type');

        $query = CrackersOrder::with('items');

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if ($orderType === 'pos') {
            $query->where(function($q) {
                $q->where('order_number', 'like', 'CRK-POS-%')
                  ->orWhere('city', 'In-Store');
            });
        } elseif ($orderType === 'online') {
            $query->where('order_number', 'not like', 'CRK-POS-%')
                  ->where(function($q) {
                      $q->whereNull('city')
                        ->orWhere('city', '!=', 'In-Store');
                  });
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders', 'search', 'status', 'orderType'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,dispatched,delivered,cancelled',
        ]);

        $order = CrackersOrder::findOrFail($id);
        $order->status = $validated['status'];
        if ($validated['status'] === 'delivered') {
            $order->payment_status = 'paid';
        }
        $order->save();

        return redirect()->back()->with('success', 'Order status updated to ' . ucfirst($validated['status']));
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $order = CrackersOrder::findOrFail($id);
        $order->payment_status = $validated['payment_status'];
        if (!empty($validated['payment_method'])) {
            $order->payment_method = $validated['payment_method'];
        }
        $order->save();

        return redirect()->back()->with('success', 'Payment status updated to ' . ucfirst($validated['payment_status']) . ' for Order #' . $order->order_number);
    }

    public function destroy($id)
    {
        $order = CrackersOrder::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted.');
    }
}
