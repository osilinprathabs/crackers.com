<?php

namespace App\Http\Controllers\Account;

use App\Models\Account\Customer;
use App\Models\Account\CustomerPayment;
use App\Models\Account\Expense;
use App\Models\Account\Revenue;
use App\Models\Account\Vendor;
use App\Models\Account\VendorPayment;
use App\Models\CrackersOrder;
use App\Models\CrackersProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Crackers.com: Admins use the ERP-style company accounting dashboard.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::user()->can('manage-account-dashboard'), 403);

        return $this->companyDashboard();
    }

    private function companyDashboard()
    {
        $creatorId = creatorId();

        $totalClients = Customer::count();
        $totalVendors = Vendor::count();

        // Crackers ERP Integrated Sales & Tax Metrics
        $totalCrackersSales = CrackersOrder::sum('grand_total');
        $onlineSales = CrackersOrder::where('is_pos', false)->sum('grand_total');
        $posSales = CrackersOrder::where('is_pos', true)->sum('grand_total');
        $totalGst = CrackersOrder::sum('gst_amount');
        $inventoryValuation = CrackersProduct::selectRaw('SUM(stock * price) as val')->value('val') ?: 0;

        $retailCustomersCount = Customer::where('customer_type', 'retail')->orWhereNull('customer_type')->count();
        $wholesaleCustomersCount = Customer::where('customer_type', 'wholesale')->count();
        $totalOrdersCount = CrackersOrder::count();

        // GL Ledger Revenue & Expense Totals
        $totalRevenue = Revenue::sum('amount') + $totalCrackersSales;
        $totalExpense = Expense::sum('amount');
        $totalRevenuePosted = Revenue::where('status', 'posted')->sum('amount') + $totalCrackersSales;
        $totalExpensePosted = Expense::where('status', 'posted')->sum('amount');
        $totalCustomerPayments = CustomerPayment::sum('payment_amount') + $totalCrackersSales;
        $totalVendorPayments = VendorPayment::sum('payment_amount');

        $netProfit = $totalRevenue - $totalExpense;
        $netProfitPosted = $totalRevenuePosted - $totalExpensePosted;

        $recentCrackersOrders = CrackersOrder::latest()->limit(5)->get();

        $recentRevenues = Revenue::latest()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->revenue_number,
                    'description' => $item->description ?? 'Revenue transaction',
                    'amount' => $item->amount,
                    'date' => $item->created_at,
                ];
            });

        $recentExpenses = Expense::latest()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->expense_number,
                    'description' => $item->description ?? 'Expense transaction',
                    'amount' => $item->amount,
                    'date' => $item->created_at,
                ];
            });

        $monthlyCustomerPayments = [];
        $monthlyVendorPayments = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M');

            $orderMonthlySales = CrackersOrder::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('grand_total');

            $custPayments = CustomerPayment::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('payment_amount');

            $vendorPayments = VendorPayment::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('payment_amount');

            $monthlyCustomerPayments[] = [
                'month' => $monthName,
                'customer_payments' => $orderMonthlySales + $custPayments,
            ];

            $monthlyVendorPayments[] = [
                'month' => $monthName,
                'vendor_payments' => $vendorPayments,
            ];
        }

        return view('admin.account.dashboard.company', [
            'stats' => [
                'total_clients' => $totalClients,
                'total_vendors' => $totalVendors,
                'total_revenue' => $totalRevenue,
                'total_expense' => $totalExpense,
                'total_revenue_posted' => $totalRevenuePosted,
                'total_expense_posted' => $totalExpensePosted,
                'total_customer_payment' => $totalCustomerPayments,
                'total_vendor_payment' => $totalVendorPayments,
                'net_profit' => $netProfit,
                'net_profit_posted' => $netProfitPosted,
            ],
            'crackersErp' => [
                'total_sales' => $totalCrackersSales,
                'online_sales' => $onlineSales,
                'pos_sales' => $posSales,
                'total_gst' => $totalGst,
                'inventory_valuation' => $inventoryValuation,
                'retail_customers' => $retailCustomersCount,
                'wholesale_customers' => $wholesaleCustomersCount,
                'total_orders' => $totalOrdersCount,
            ],
            'monthlyCustomerPayments' => $monthlyCustomerPayments,
            'monthlyVendorPayments' => $monthlyVendorPayments,
            'recentRevenues' => $recentRevenues,
            'recentExpenses' => $recentExpenses,
            'recentCrackersOrders' => $recentCrackersOrders,
        ]);
    }
}

