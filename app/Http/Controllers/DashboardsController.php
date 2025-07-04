<?php

// app/Http/Controllers/DashboardsController.php
namespace App\Http\Controllers;

use App\Models\BudgetGoal;
use App\Models\MonthlySummary;
use App\Models\DailyRecord;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardsController extends Controller
{
    public function index(Request $request)
    {
        // 現在のユーザーIDを取得
        $user = Auth::user();
        $year = $request->get('year', date('Y'));
        
        // ユーザーの目標レコードを取得または作成
        $budgetGoal = BudgetGoal::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => $year  // yearも条件に追加
            ],
            [
                'annual_savings_target' => 0,
                'year' => $year  // 作成時のデフォルト値にも設定
            ]
        );
    
        $month = $request->get('month', date('n'));
        
        // 今月のサマリー
        $currentMonthSummary = DailyRecord::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->selectRaw('SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as total_income')
            ->selectRaw('SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END) as total_expense')
            ->selectRaw('SUM(amount) as balance')
            ->first();
       
        // 年間目標取得
        $budgetGoal = BudgetGoal::getCurrentYearGoal();
        
        // 今月のサマリーを DailyRecord から直接計算
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        
        $monthlyRecords = DailyRecord::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();
            
        // 月別サマリー (フィルタ条件適用)            
        $monthlySummary = (object) [
            'total_income' => 0,
            'total_expense' => 0,
            'balance' => 0,
            'year' => $year,
            'month' => $month
        ];           
        
        if ($monthlyRecords->isNotEmpty()) {
            $totalIncome = $monthlyRecords->where('amount', '>', 0)->sum('amount');
            $totalExpense = $monthlyRecords->where('amount', '<', 0)->sum('amount');
            
            $monthlySummary = (object) [
                'total_income' => $totalIncome,
                'total_expense' => abs($totalExpense),
                'balance' => $totalIncome + $totalExpense,
                'year' => $year,
                'month' => $month
            ];
        }
        
        // 累計貯蓄額（今年の全記録の収支合計）
        $yearRecords = DailyRecord::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->get();
            
        $totalSavings = $yearRecords->sum('amount');
        
        // 目標達成率
        $achievementRate = $budgetGoal && $budgetGoal->annual_savings_target > 0 
                         ? ($totalSavings / $budgetGoal->annual_savings_target) * 100 
                         : 0;
        
        // 月別データ（グラフ用）- DailyRecord から計算
        $monthlyData = collect();
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::create($year, $m, 1)->startOfMonth();
            $monthEnd = Carbon::create($year, $m, 1)->endOfMonth();
            
            $records = DailyRecord::where('user_id', $user->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get();
            
            $income = $records->where('amount', '>', 0)->sum('amount');
            $expense = $records->where('amount', '<', 0)->sum('amount');
            
            $monthlyData->push([
                'month' => $m,
                'income' => $income,
                'expense' => abs($expense),
                'balance' => $income + $expense
            ]);
        }
        
        $dailyRecords = DailyRecord::where('user_id', auth()->id())
            ->whereYear('date', $year)
            ->when($month, function($query) use ($month) {
                return $query->whereMonth('date', $month);
            })
            ->orderBy('date', 'desc')
            ->get();
        
        return view('dashboard.index', [
            'currentMonthSummary' => $currentMonthSummary ?? (object)[
                'total_income' => 0,
                'total_expense' => 0,
                'balance' => 0
            ],        
            'budgetGoal' => $budgetGoal,
            'currentMonthlySummary' => $monthlySummary,
            'totalSavings' => $totalSavings,
            'achievementRate' => $achievementRate,
            'monthlyData' => $monthlyData,
            'dailyRecords' => $dailyRecords ?? [], // 定義済みの変数を渡す
            'dashboardData' => $dashboardData ?? [], // タイポも修正 (dashboardDta → dashboardData)
            'year' => $year,
            'month' => $month,
        ]);
    }
}
