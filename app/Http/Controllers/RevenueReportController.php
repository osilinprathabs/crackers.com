<?php

namespace App\Http\Controllers;

use App\Models\CrackersOrder;
use App\Models\CrackersSetting;
use App\Models\Account\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueReportController extends Controller
{
    /**
     * Index View for Crackers Revenue & Sales Report
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $paymentStatus = $request->input('payment_status', 'all');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = CrackersOrder::with('items');

        if ($fromDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($toDate));
        }

        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $allFilteredOrders = (clone $query)->get();

        $totalSubtotal = $allFilteredOrders->sum('subtotal');
        $totalGstAmount = $allFilteredOrders->sum('gst_amount');
        $overallTotalRevenue = $allFilteredOrders->sum('grand_total');
        $totalOrdersCount = $allFilteredOrders->count();
        $paidOrdersCount = $allFilteredOrders->where('payment_status', 'paid')->count();
        $pendingOrdersCount = $allFilteredOrders->where('payment_status', 'pending')->count();

        $orders = $query->latest()->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return view('admin.revenue.table', compact('orders'))->render();
        }

        return view('admin.revenue.index', compact(
            'orders',
            'search',
            'paymentStatus',
            'fromDate',
            'toDate',
            'totalSubtotal',
            'totalGstAmount',
            'overallTotalRevenue',
            'totalOrdersCount',
            'paidOrdersCount',
            'pendingOrdersCount'
        ));
    }

    public function export(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = CrackersOrder::query();

        if ($fromDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($toDate));
        }

        $orders = $query->latest()->get();

        $csvData = "Order Number,Customer Name,Phone,Subtotal,GST Amount,Grand Total,Payment Method,Payment Status,Date\n";
        foreach ($orders as $order) {
            $csvData .= "\"{$order->order_number}\",\"{$order->customer_name}\",\"{$order->customer_phone}\",{$order->subtotal},{$order->gst_amount},{$order->grand_total},\"{$order->payment_method}\",\"{$order->payment_status}\",\"{$order->created_at}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="crackers_revenue_report_' . date('Y_m_d') . '.csv"',
        ]);
    }
}
