<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalStaff = \App\Models\Staff::count();
        $totalUsers = \App\Models\User::count();
        
        $bankAccountsCount = \Illuminate\Support\Facades\Schema::hasTable('bank_accounts') ? \App\Models\Account\BankAccount::count() : 0;
        $totalRevenues = \Illuminate\Support\Facades\Schema::hasTable('revenues') ? \App\Models\Account\Revenue::sum('amount') : 0;
        $totalExpenses = \Illuminate\Support\Facades\Schema::hasTable('expenses') ? \App\Models\Account\Expense::sum('amount') : 0;

        return view('admin.dashboard', compact(
            'totalStaff',
            'totalUsers',
            'bankAccountsCount',
            'totalRevenues',
            'totalExpenses'
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
