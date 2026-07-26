<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Emi;
use App\Models\LoanAccount;
use App\Models\LoanApplication;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    if (Auth::check() && Auth::user()->hasRole('CreditVerifier')) {
      return redirect()->route('verification-credit-score-history');
    }

    $totalAgents = Agent::count();
    $totalStaff = \App\Models\Staff::count();

    $clientPeriod = $request->get('client_period', 'all');
    $clientPeriodLabel = 'All Time';
    $clientDateLimit = null;

    if ($clientPeriod === '28_days') {
      $clientDateLimit = Carbon::now()->subDays(28);
      $clientPeriodLabel = 'Last 28 Days';
    } elseif ($clientPeriod === 'month') {
      $clientDateLimit = Carbon::now()->startOfMonth();
      $clientPeriodLabel = 'This Month';
    } elseif ($clientPeriod === 'year') {
      $clientDateLimit = Carbon::now()->startOfYear();
      $clientPeriodLabel = 'This Year';
    }

    $currentUser = Auth::user();
    $isAgent = $currentUser->hasRole('Agent');
    $agentId = $isAgent ? optional($currentUser->agent)->id : null;

    $clientQuery = Client::query();
    if ($isAgent && $agentId) {
      $clientQuery->where(function($q) use ($agentId) {
        $q->where('assigned_to', $agentId)
          ->orWhere('added_by', $agentId);
      });
    }

    if ($clientDateLimit) {
      $clientQuery->where('created_at', '>=', $clientDateLimit);
    }

    $clientAggregates = $clientQuery->selectRaw("LOWER(TRIM(status)) as normalized_status")
      ->selectRaw('COUNT(*) as total')
      ->groupBy('normalized_status')
      ->pluck('total', 'normalized_status');

    $totalClients = $clientAggregates->sum();

    // Count active clients (includes both 'active' and 'verified' status)
    $activeClients = (int) (($clientAggregates['active'] ?? 0) + ($clientAggregates['verified'] ?? 0));

    // Count blacklisted clients
    $blacklistedClients = (int) (($clientAggregates['blacklist'] ?? 0) + ($clientAggregates['blacklisted'] ?? 0));

    // Count inactive clients (includes both 'inactive' and 'unverified' status)
    $inactiveClients = (int) (($clientAggregates['inactive'] ?? 0) + ($clientAggregates['unverified'] ?? 0));

    // Count pending clients
    $pendingClients = (int) ($clientAggregates['pending'] ?? 0);


    $loanQuery = LoanAccount::query();
    if ($isAgent && $agentId) {
      $loanQuery->whereHas('client', function($q) use ($agentId) {
        $q->where('assigned_to', $agentId)
          ->orWhere('added_by', $agentId);
      });
    }

    $loanAggregates = (clone $loanQuery)->selectRaw("LOWER(TRIM(status)) as normalized_status")
      ->selectRaw('COUNT(*) as total')
      ->groupBy('normalized_status')
      ->pluck('total', 'normalized_status');

    $totalLoans = $loanAggregates->sum();
    $activeLoans = (int) ($loanAggregates['active'] ?? 0);

    $appQuery = LoanApplication::query();
    if ($isAgent && $agentId) {
      $appQuery->whereHas('client', function($q) use ($agentId) {
        $q->where('assigned_to', $agentId)
          ->orWhere('added_by', $agentId);
      });
    }

    $pendingApplications = (clone $appQuery)->whereRaw("LOWER(TRIM(status)) = 'pending'")->count();
    $totalDisbursedAmount = (clone $loanQuery)->sum('loan_amount');
    $totalOutstandingAmount = (clone $loanQuery)->sum('outstanding_amount');

    // Calculate uncollected interest amount
    $uncollectedInterest = 0;
    $loansForUncollected = (clone $loanQuery)->where('status', 'active')->with('emis')->get();
    foreach ($loansForUncollected as $loan) {
      foreach ($loan->emis as $emi) {
        $interestCollected = 0;
        if ($loan->loan_mode === 'interest_only') {
          $interestCollected = max(0.00, (float)$emi->paid_amount - (float)$emi->principal_amount);
        } else {
          $interestCollected = min((float)$emi->paid_amount, (float)$emi->interest_amount);
        }
        $uncollectedInterest += max(0.00, (float)$emi->interest_amount - $interestCollected);
      }
    }

    $emiQuery = Emi::query();
    if ($isAgent && $agentId) {
      $emiQuery->whereHas('loanAccount.client', function($q) use ($agentId) {
        $q->where('assigned_to', $agentId)
          ->orWhere('added_by', $agentId);
      });
    }

    $overdueLoans = (clone $emiQuery)->whereRaw("LOWER(TRIM(status)) = 'overdue'")
      ->distinct('loan_account_id')
      ->count('loan_account_id');

    $now = Carbon::now();
    
    // Revenue from paid EMIs (Interest + Penalty)
    $revenueEmiQuery = (clone $emiQuery)->whereRaw("LOWER(TRIM(status)) = 'paid'")
      ->whereNotNull('paid_date')
      ->whereMonth('paid_date', $now->month)
      ->whereYear('paid_date', $now->year);

    $emiRevenue = $revenueEmiQuery->sum(DB::raw('COALESCE(interest_amount, 0) + COALESCE(penalty_amount, 0)'));

    // Revenue from disbursed loans this month (Processing fee + Document charges + Other charges)
    $revenueAppQuery = (clone $appQuery)->whereMonth('disbursed_at', $now->month)
      ->whereYear('disbursed_at', $now->year)
      ->whereRaw("LOWER(TRIM(status)) = 'disbursed'")
      ->with('applicationDetail');

    $loansDisbursedThisMonth = $revenueAppQuery->get();

    $feeRevenue = $loansDisbursedThisMonth->sum(function($loan) {
        $details = $loan->applicationDetail->details ?? [];
        return (float)($details['applied_processing_fee'] ?? 0) 
             + (float)($details['applied_document_charges'] ?? 0) 
             + (float)($details['applied_other_charges'] ?? 0);
    });

    $revenueThisMonth = $emiRevenue + $feeRevenue;

    // Total Revenue (All-Time - aligning with RevenueReportController)
    $allLoansForRevenue = LoanAccount::with(['loanApplication.applicationDetail', 'emis'])->get();
    
    $totalProcessingFees = 0;
    $totalDocumentCharges = 0;
    $totalOtherCharges = 0;
    $totalInterestCollected = 0;
    $totalForeclosureRevenue = 0;
    $totalPenaltyAmount = 0;

    foreach ($allLoansForRevenue as $loan) {
        $details = $loan->loanApplication->applicationDetail->details ?? [];
        $processingFee = (float)($details['applied_processing_fee'] ?? 0);
        $documentCharges = (float)($details['applied_document_charges'] ?? 0);
        $otherCharges = (float)($details['applied_other_charges'] ?? 0);

        $interestCollected = $loan->emis->sum(function ($emi) use ($loan) {
            if ($loan->loan_mode === 'interest_only') {
                return max(0.00, (float)$emi->paid_amount - (float)$emi->principal_amount);
            } else {
                return min((float)$emi->paid_amount, (float)$emi->interest_amount);
            }
        });

        $foreclosureRevenue = 0;
        if ($loan->is_foreclosed && $loan->foreclosure_amount > 0) {
            $chargesPercentage = $loan->foreclosure_charges_percentage ?? $loan->getForeclosureChargesPercentage();
            $multiplier = 1 + ($chargesPercentage / 100);
            $outstanding = $loan->foreclosure_amount / $multiplier;
            $foreclosureRevenue = $loan->foreclosure_amount - $outstanding;
        }

        $penaltyAmount = $loan->emis->sum('penalty_amount');

        $totalProcessingFees += $processingFee;
        $totalDocumentCharges += $documentCharges;
        $totalOtherCharges += $otherCharges;
        $totalInterestCollected += $interestCollected;
        $totalForeclosureRevenue += $foreclosureRevenue;
        $totalPenaltyAmount += $penaltyAmount;
    }

    $totalRevenue = $totalProcessingFees + $totalDocumentCharges + $totalOtherCharges + $totalInterestCollected + $totalForeclosureRevenue + $totalPenaltyAmount;

    // Determine 12-month window. Prefer last 12 months; if no data, align with first upcoming EMI.
    $defaultWindowStart = $now->copy()->subMonths(11)->startOfMonth();
    $defaultWindowEnd = $now->copy()->endOfMonth();

    $firstDueDate = Emi::whereNotNull('due_date')->orderBy('due_date')->value('due_date');
    $emiWindowStart = $defaultWindowStart;

    if ($firstDueDate) {
      $firstDueMonth = Carbon::parse($firstDueDate)->startOfMonth();

      // If the earliest EMI is beyond the default window, shift the window forward
      if ($firstDueMonth->gt($defaultWindowEnd)) {
        $emiWindowStart = $firstDueMonth;
      }
    }

    $emiWindowEnd = $emiWindowStart->copy()->addMonthsNoOverflow(11)->endOfMonth();

    // EMI Collection (last 12 months)
    $chartEmiQuery = (clone $emiQuery)->select(
      DB::raw('DATE_FORMAT(due_date, "%Y-%m") as month_key'),
      DB::raw('SUM(total_amount) as due_amount'),
      DB::raw('SUM(CASE WHEN status = "paid" THEN COALESCE(paid_amount, total_amount) ELSE 0 END) as collected_amount')
    )
      ->whereNotNull('due_date')
      ->whereBetween('due_date', [$emiWindowStart, $emiWindowEnd])
      ->groupBy('month_key')
      ->orderBy('month_key');

    $emiCollectionRaw = $chartEmiQuery->get();

    $emiMonthsWindow = collect(range(0, 11))->map(fn($i) => $emiWindowStart->copy()->addMonthsNoOverflow($i));

    $emiChartBuckets = $emiMonthsWindow->map(function (Carbon $date) use ($emiCollectionRaw) {
      $monthKey = $date->format('Y-m');
      $match = $emiCollectionRaw->firstWhere('month_key', $monthKey);

      $due = $match ? (float) $match->due_amount : 0.0;
      $collected = $match ? (float) $match->collected_amount : 0.0;

      return [
        'label' => $date->format('M Y'),
        'collected' => round($collected, 2),
        'pending' => round(max($due - $collected, 0), 2),
      ];
    });

    $emiChart = [
      'labels' => $emiChartBuckets->pluck('label')->values()->all(),
      'collected' => $emiChartBuckets->pluck('collected')->values()->all(),
      'pending' => $emiChartBuckets->pluck('pending')->values()->all(),
      'hasData' => ($emiChartBuckets->sum('collected') + $emiChartBuckets->sum('pending')) > 0,
    ];

    // Loan performance overview (disbursed amount by product)
    $loanPerformanceRaw = LoanAccount::selectRaw(
      'COALESCE(loan_products.loan_name, loan_accounts.loan_code, "Unknown") as loan_name'
    )
      ->selectRaw('COUNT(loan_accounts.id) as loan_count')
      ->selectRaw('SUM(COALESCE(loan_accounts.loan_amount, 0)) as total_disbursed')
      ->leftJoin('loan_products', 'loan_products.loan_code', '=', 'loan_accounts.loan_code')
      ->groupBy('loan_products.loan_name', 'loan_accounts.loan_code')
      ->orderByDesc(DB::raw('SUM(COALESCE(loan_accounts.loan_amount, 0))'))
      ->get();

    $loanPerformanceTotalAmount = (float) $loanPerformanceRaw->sum('total_disbursed');
    $loanPerformanceTotalCount = (int) $loanPerformanceRaw->sum('loan_count');

    $iconPresets = [
      'personal' => ['icon' => 'ri-user-heart-line', 'color' => 'primary'],
      'vehicle' => ['icon' => 'ri-car-line', 'color' => 'info'],
      'auto' => ['icon' => 'ri-car-line', 'color' => 'info'],
      'home' => ['icon' => 'ri-home-4-line', 'color' => 'success'],
      'business' => ['icon' => 'ri-briefcase-4-line', 'color' => 'warning'],
      'education' => ['icon' => 'ri-book-3-line', 'color' => 'danger'],
      'agri' => ['icon' => 'ri-plant-line', 'color' => 'success'],
      'gold' => ['icon' => 'ri-gift-line', 'color' => 'warning'],
      'micro' => ['icon' => 'ri-community-line', 'color' => 'primary'],
    ];

    $loanPerformanceList = $loanPerformanceRaw->map(function ($record) use ($loanPerformanceTotalAmount, $iconPresets) {
      $loanName = $record->loan_name;
      $preset = collect($iconPresets)->first(function ($config, $needle) use ($loanName) {
        return Str::contains(Str::lower($loanName), $needle);
      }, ['icon' => 'ri-bank-card-line', 'color' => 'secondary']);

      $sharePercent = $loanPerformanceTotalAmount > 0
        ? round(($record->total_disbursed / $loanPerformanceTotalAmount) * 100, 1)
        : 0;

      return [
        'name' => $loanName,
        'loan_count' => (int) $record->loan_count,
        'total_disbursed' => (float) $record->total_disbursed,
        'share_percent' => $sharePercent,
        'icon' => $preset['icon'],
        'color' => $preset['color'],
      ];
    })->sortByDesc('total_disbursed')->values();

    $loanPerformanceChart = ['hasData' => false];

    $loanDistributionChart = [
      'labels' => $loanPerformanceList->pluck('name')->values()->all(),
      'series' => $loanPerformanceList->pluck('loan_count')->map(fn($value) => (int) $value)->values()->all(),
      'total' => $loanPerformanceTotalCount,
      'hasData' => $loanPerformanceList->isNotEmpty(),
    ];

    $recentApplications = LoanApplication::with(['client.user:id,name', 'client.location', 'product:loan_code,loan_name,loan_amount_max'])
      ->orderByDesc('created_at')
      ->take(5)
      ->get()
      ->map(function ($application) {
        // Use credit limit for pending/approved applications, actual loan_amount for others
        $creditLimit = optional($application->product)->loan_amount_max ?? null;
        $shouldShowCreditLimit = in_array($application->status, ['pending', 'approved']);
        $application->display_amount = $shouldShowCreditLimit ? $creditLimit : $application->loan_amount;
        return $application;
      });

    $clientActivityPercentage = $totalClients > 0
      ? round(($activeClients / $totalClients) * 100, 2)
      : 0;

    return view('admin.dashboard', compact(
      'totalAgents',
      'totalStaff',
      'totalClients',
      'totalLoans',
      'activeLoans',
      'pendingApplications',
      'totalDisbursedAmount',
      'totalOutstandingAmount',
      'uncollectedInterest',
      'overdueLoans',
      'revenueThisMonth',
      'totalRevenue',
      'activeClients',
      'inactiveClients',
      'pendingClients',
      'blacklistedClients',
      'clientPeriodLabel',
      'emiChart',
      'loanPerformanceChart',
      'loanDistributionChart',
      'loanPerformanceList',
      'loanPerformanceTotalAmount',
      'loanPerformanceTotalCount',
      'recentApplications',
      'clientActivityPercentage'
    ));
  }

  /**
   * Get real-time statistics for the dashboard
   */
  public function getStats()
  {
    $totalAgents = Agent::count();
    $totalStaff = \App\Models\Staff::count();

    $clientAggregates = Client::selectRaw("LOWER(TRIM(status)) as normalized_status")
      ->selectRaw('COUNT(*) as total')
      ->groupBy('normalized_status')
      ->pluck('total', 'normalized_status');

    $totalClients = $clientAggregates->sum();
    $activeClients = (int) (($clientAggregates['active'] ?? 0) + ($clientAggregates['verified'] ?? 0));
    $blacklistedClients = (int) (($clientAggregates['blacklist'] ?? 0) + ($clientAggregates['blacklisted'] ?? 0));
    $inactiveClients = (int) (($clientAggregates['inactive'] ?? 0) + ($clientAggregates['unverified'] ?? 0));
    $pendingClients = (int) ($clientAggregates['pending'] ?? 0);

    $loanAggregates = LoanAccount::selectRaw("LOWER(TRIM(status)) as normalized_status")
      ->selectRaw('COUNT(*) as total')
      ->groupBy('normalized_status')
      ->pluck('total', 'normalized_status');

    $totalLoans = $loanAggregates->sum();
    $activeLoans = (int) ($loanAggregates['active'] ?? 0);

    $pendingApplications = LoanApplication::whereRaw("LOWER(TRIM(status)) = 'pending'")->count();
    $totalDisbursedAmount = LoanAccount::sum('loan_amount');
    $totalOutstandingAmount = LoanAccount::sum('outstanding_amount');
    $overdueLoans = Emi::whereRaw("LOWER(TRIM(status)) = 'overdue'")
      ->distinct('loan_account_id')
      ->count('loan_account_id');

    // Calculate uncollected interest amount
    $uncollectedInterest = 0;
    $loansForUncollected = LoanAccount::where('status', 'active')->with('emis')->get();
    foreach ($loansForUncollected as $loan) {
      foreach ($loan->emis as $emi) {
        $interestCollected = 0;
        if ($loan->loan_mode === 'interest_only') {
          $interestCollected = max(0.00, (float)$emi->paid_amount - (float)$emi->principal_amount);
        } else {
          $interestCollected = min((float)$emi->paid_amount, (float)$emi->interest_amount);
        }
        $uncollectedInterest += max(0.00, (float)$emi->interest_amount - $interestCollected);
      }
    }

    $now = Carbon::now();
    
    // Revenue from paid EMIs (Interest + Penalty)
    $emiRevenue = Emi::whereRaw("LOWER(TRIM(status)) = 'paid'")
      ->whereNotNull('paid_date')
      ->whereMonth('paid_date', $now->month)
      ->whereYear('paid_date', $now->year)
      ->sum(DB::raw('COALESCE(interest_amount, 0) + COALESCE(penalty_amount, 0)'));

    // Revenue from disbursed loans this month (Processing fee + Document charges + Other charges)
    $loansDisbursedThisMonth = LoanApplication::whereMonth('disbursed_at', $now->month)
      ->whereYear('disbursed_at', $now->year)
      ->whereRaw("LOWER(TRIM(status)) = 'disbursed'")
      ->with('applicationDetail')
      ->get();

    $feeRevenue = $loansDisbursedThisMonth->sum(function($loan) {
        $details = $loan->applicationDetail->details ?? [];
        return (float)($details['applied_processing_fee'] ?? 0) 
             + (float)($details['applied_document_charges'] ?? 0) 
             + (float)($details['applied_other_charges'] ?? 0);
    });

    $revenueThisMonth = $emiRevenue + $feeRevenue;

    // EMI Chart Data (Performance)
    $months = [];
    $collected = [];
    $pending = [];
    for ($i = 5; $i >= 0; $i--) {
      $monthDate = Carbon::now()->subMonths($i);
      $months[] = $monthDate->format('M Y');
      
      $collected[] = Emi::whereMonth('paid_date', $monthDate->month)
        ->whereYear('paid_date', $monthDate->year)
        ->whereRaw("LOWER(TRIM(status)) = 'paid'")
        ->sum('paid_amount');
        
      $pending[] = Emi::whereMonth('due_date', $monthDate->month)
        ->whereYear('due_date', $monthDate->year)
        ->whereRaw("LOWER(TRIM(status)) != 'paid'")
        ->sum('amount');
    }

    // Loan Distribution Data
    $distributionData = \App\Models\LoanProduct::withCount(['loanAccounts' => function($q) {
      $q->whereRaw("LOWER(TRIM(status)) = 'active'");
    }])->get();
    
    $distLabels = $distributionData->pluck('loan_name')->toArray();
    $distSeries = $distributionData->pluck('loan_accounts_count')->toArray();

    return response()->json([
      'success' => true,
      'stats' => [
        'totalAgents' => number_format($totalAgents),
        'totalStaff' => number_format($totalStaff),
        'totalClients' => number_format($totalClients),
        'activeClients' => number_format($activeClients),
        'inactiveClients' => number_format($inactiveClients),
        'pendingClients' => number_format($pendingClients),
        'blacklistedClients' => number_format($blacklistedClients),
        'totalLoans' => number_format($totalLoans),
        'activeLoans' => number_format($activeLoans),
        'pendingApplications' => number_format($pendingApplications),
        'totalDisbursedAmount' => number_format($totalDisbursedAmount, 2),
        'totalOutstandingAmount' => number_format($totalOutstandingAmount, 2),
        'uncollectedInterest' => number_format($uncollectedInterest, 2),
        'overdueLoans' => number_format($overdueLoans),
        'revenueThisMonth' => number_format($revenueThisMonth, 2),
      ],
      'charts' => [
        'emiChart' => [
          'labels' => $months,
          'collected' => $collected,
          'pending' => $pending,
          'hasData' => count($months) > 0
        ],
        'loanDistribution' => [
          'labels' => $distLabels,
          'series' => $distSeries,
          'hasData' => array_sum($distSeries) > 0,
          'total' => array_sum($distSeries)
        ]
      ]
    ]);
  }
}
