<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Account\Customer;
use App\Models\CrackersOrder;
use App\Models\CrackersProduct;
use App\Models\CrackersCategory;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        // Base Query
        $query = CrackersOrder::query();

        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } else {
            switch ($period) {
                case 'daily':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'weekly':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'yearly':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'monthly':
                default:
                    $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        }

        $filteredOrdersCount = (clone $query)->count();
        $filteredSalesRevenue = (clone $query)->where('payment_status', 'paid')->sum('grand_total');
        if ($filteredSalesRevenue == 0) {
            $filteredSalesRevenue = (clone $query)->sum('grand_total');
        }

        // Global Totals
        $totalCustomers = Customer::count();
        $totalOrders = CrackersOrder::count();
        $totalSales = CrackersOrder::where('payment_status', 'paid')->sum('grand_total') ?: CrackersOrder::sum('grand_total');
        $totalProducts = CrackersProduct::count();
        $pendingOrdersCount = CrackersOrder::where('status', 'pending')->count();
        $totalCategories = CrackersCategory::where('status', true)->count();

        // Group Recent Orders Day-Wise
        $recentOrdersGrouped = (clone $query)->with('items')->latest()->take(20)->get()->groupBy(function($order) {
            if ($order->created_at->isToday()) {
                return 'Today (' . $order->created_at->format('d M Y') . ')';
            } elseif ($order->created_at->isYesterday()) {
                return 'Yesterday (' . $order->created_at->format('d M Y') . ')';
            } else {
                return $order->created_at->format('d M Y (l)');
            }
        });

        if ($recentOrdersGrouped->isEmpty()) {
            $recentOrdersGrouped = CrackersOrder::with('items')->latest()->take(20)->get()->groupBy(function($order) {
                if ($order->created_at->isToday()) {
                    return 'Today (' . $order->created_at->format('d M Y') . ')';
                } elseif ($order->created_at->isYesterday()) {
                    return 'Yesterday (' . $order->created_at->format('d M Y') . ')';
                } else {
                    return $order->created_at->format('d M Y (l)');
                }
            });
        }

        $featuredProducts = CrackersProduct::where('status', true)->where('is_featured', true)->take(4)->get();
        $totalStaff = \App\Models\Staff::count();
        $totalUsers = \App\Models\User::count();
        $totalRevenues = \Illuminate\Support\Facades\Schema::hasTable('revenues') ? \App\Models\Account\Revenue::sum('amount') : 0;
        $totalExpenses = \Illuminate\Support\Facades\Schema::hasTable('expenses') ? \App\Models\Account\Expense::sum('amount') : 0;

        // 1. Sales Trend Graph Data
        $chartGroupFormat = match($period) {
            'daily' => '%H:00',
            'weekly' => '%d %b',
            'yearly' => '%b %Y',
            default => '%d %b',
        };

        $trendDataRaw = (clone $query)
            ->select([
                DB::raw("DATE_FORMAT(created_at, '{$chartGroupFormat}') as period_label"),
                DB::raw("SUM(grand_total) as total_sales"),
                DB::raw("COUNT(id) as total_orders")
            ])
            ->groupBy('period_label')
            ->orderBy('period_label', 'ASC')
            ->get();

        $chartLabels = $trendDataRaw->pluck('period_label')->toArray();
        $chartSalesData = $trendDataRaw->pluck('total_sales')->map(fn($v) => floatval($v))->toArray();
        $chartOrdersData = $trendDataRaw->pluck('total_orders')->map(fn($v) => intval($v))->toArray();

        // 2. Category-Based Sales % Breakdown
        $categorySalesRaw = DB::table('crackers_order_items')
            ->join('crackers_orders', 'crackers_order_items.order_id', '=', 'crackers_orders.id')
            ->leftJoin('crackers_products', 'crackers_order_items.product_id', '=', 'crackers_products.id')
            ->when(!empty($fromDate) && !empty($toDate), function($q) use ($fromDate, $toDate) {
                $q->whereBetween('crackers_orders.created_at', [
                    Carbon::parse($fromDate)->startOfDay(),
                    Carbon::parse($toDate)->endOfDay()
                ]);
            })
            ->when(empty($fromDate) || empty($toDate), function($q) use ($period) {
                switch ($period) {
                    case 'daily':
                        $q->whereDate('crackers_orders.created_at', Carbon::today());
                        break;
                    case 'weekly':
                        $q->whereBetween('crackers_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'yearly':
                        $q->whereYear('crackers_orders.created_at', Carbon::now()->year);
                        break;
                    case 'monthly':
                    default:
                        $q->whereMonth('crackers_orders.created_at', Carbon::now()->month)->whereYear('crackers_orders.created_at', Carbon::now()->year);
                        break;
                }
            })
            ->select([
                DB::raw("COALESCE(crackers_products.category, 'General') as category_name"),
                DB::raw('SUM(crackers_order_items.total_price) as category_total')
            ])
            ->groupBy('category_name')
            ->get();

        $totalCategorySum = $categorySalesRaw->sum('category_total');
        $categorySalesBreakdown = [];
        foreach ($categorySalesRaw as $item) {
            $catTotal = floatval($item->category_total);
            $percentage = $totalCategorySum > 0 ? round(($catTotal / $totalCategorySum) * 100, 1) : 0;
            $categorySalesBreakdown[] = [
                'category' => $item->category_name ?: 'General',
                'total' => $catTotal,
                'percentage' => $percentage
            ];
        }

        if (empty($categorySalesBreakdown)) {
            $dbCategories = CrackersCategory::where('status', true)->pluck('name')->toArray();
            $catCount = count($dbCategories) ?: 1;
            $dummyPct = round(100 / $catCount, 1);
            foreach ($dbCategories as $cName) {
                $categorySalesBreakdown[] = [
                    'category' => $cName,
                    'total' => 0,
                    'percentage' => $dummyPct
                ];
            }
        }

        return view('admin.dashboard', compact(
            'period',
            'fromDate',
            'toDate',
            'filteredOrdersCount',
            'filteredSalesRevenue',
            'totalCustomers',
            'totalOrders',
            'totalSales',
            'totalProducts',
            'pendingOrdersCount',
            'totalCategories',
            'recentOrdersGrouped',
            'featuredProducts',
            'totalStaff',
            'totalUsers',
            'totalRevenues',
            'totalExpenses',
            'chartLabels',
            'chartSalesData',
            'chartOrdersData',
            'categorySalesBreakdown'
        ));
    }

    public function getStats(Request $request)
    {
        return response()->json([
            'totalStaff' => \App\Models\Staff::count(),
            'totalUsers' => \App\Models\User::count(),
            'totalRevenues' => \Illuminate\Support\Facades\Schema::hasTable('revenues') ? \App\Models\Account\Revenue::sum('amount') : 0,
            'totalExpenses' => \Illuminate\Support\Facades\Schema::hasTable('expenses') ? \App\Models\Account\Expense::sum('amount') : 0,
        ]);
    }
}
