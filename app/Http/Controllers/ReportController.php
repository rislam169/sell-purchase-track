<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Budget;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Expense::select(
                DB::raw('sum(total_cost) as total'),
                DB::raw("DATE_FORMAT(created_at,'%c') as months")
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('months')
            ->pluck('total', 'months')
            ->toArray();
        $totalExpenseStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $totalExpenseStats[$i] = $orders[$i] ?? 0;
        }

        $purchases = Budget::select(
                DB::raw('sum(total_cost) as total'),
                DB::raw("DATE_FORMAT(created_at,'%c') as months")
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('months')
            ->pluck('total', 'months')
            ->toArray();
        $totalBudgetStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $totalBudgetStats[$i] = $purchases[$i] ?? 0;
        }

        $data = [
            'menu' => 'dashboard',
            'totalProject' => Project::count(),
            'totalBudget' => Budget::whereMonth('created_at', Carbon::now()->month)->count(),
            'totalBudgetAmount' => Budget::whereMonth('created_at', Carbon::now()->month)->sum('total_cost'),
            'totalExpense' => Expense::whereMonth('created_at', Carbon::now()->month)->count(),
            'totalExpenseAmount' => Expense::whereMonth('created_at', Carbon::now()->month)->sum('total_cost'),
            'totalExpenseStats' => json_encode($totalExpenseStats),
            'totalBudgetStats' => json_encode($totalBudgetStats)
        ];

        return view('report.index')->with($data);
    }
}
