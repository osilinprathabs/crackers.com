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
        $dateFilter = $request->query('date_filter', 'today'); // Default: Today (Daily)
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = CrackersOrder::with('items');

        // Helper to apply date constraints
        $applyDateFilter = function(&$q) use ($dateFilter, $dateFrom, $dateTo) {
            if ($dateFilter === 'today') {
                $q->whereDate('created_at', \Carbon\Carbon::today());
            } elseif ($dateFilter === 'yesterday') {
                $q->whereDate('created_at', \Carbon\Carbon::yesterday());
            } elseif ($dateFilter === 'this_week') {
                $q->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($dateFilter === 'this_month') {
                $q->whereMonth('created_at', \Carbon\Carbon::now()->month)->whereYear('created_at', \Carbon\Carbon::now()->year);
            } elseif ($dateFilter === 'custom') {
                if (!empty($dateFrom)) {
                    $q->whereDate('created_at', '>=', $dateFrom);
                }
                if (!empty($dateTo)) {
                    $q->whereDate('created_at', '<=', $dateTo);
                }
            }
        };

        $applyDateFilter($query);

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

        // Calculate status counts (respecting date filter, search and order_type)
        $statusCountsQuery = CrackersOrder::query();
        $applyDateFilter($statusCountsQuery);

        if ($orderType === 'pos') {
            $statusCountsQuery->where(function($q) {
                $q->where('order_number', 'like', 'CRK-POS-%')
                  ->orWhere('city', 'In-Store');
            });
        } elseif ($orderType === 'online') {
            $statusCountsQuery->where('order_number', 'not like', 'CRK-POS-%')
                  ->where(function($q) {
                      $q->whereNull('city')
                        ->orWhere('city', '!=', 'In-Store');
                  });
        }

        if (!empty($search)) {
            $statusCountsQuery->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }

        $statusCounts = [
            'all' => (clone $statusCountsQuery)->count(),
            'pending' => (clone $statusCountsQuery)->where('status', 'pending')->count(),
            'processing' => (clone $statusCountsQuery)->where('status', 'processing')->count(),
            'dispatched' => (clone $statusCountsQuery)->where('status', 'dispatched')->count(),
            'delivered' => (clone $statusCountsQuery)->where('status', 'delivered')->count(),
            'cancelled' => (clone $statusCountsQuery)->where('status', 'cancelled')->count(),
        ];

        $totalPeriodRevenue = (clone $query)->where('status', '!=', 'cancelled')->sum('grand_total');

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders', 'search', 'status', 'orderType', 'statusCounts', 'dateFilter', 'dateFrom', 'dateTo', 'totalPeriodRevenue'));
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
