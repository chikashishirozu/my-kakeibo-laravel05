<?php

// app/Http/Controllers/DailyRecordsController.php
namespace App\Http\Controllers;

use App\Models\BudgetGoal;
use App\Models\DailyRecord;
use App\Models\MonthlySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;

class DailyRecordsController extends Controller
{
    public function index(Request $request)
    {
        // 現在のユーザーIDを取得
        $user = Auth::user();
        $year = (int)$request->get('year', date('Y'));
        
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
        
        $records = DailyRecord::byMonth($year, $month)
                            ->whereYear('date', $year)
                            ->when($month, function($query) use ($month) {
                                return $query->whereMonth('date', $month);
                            })        
                             ->orderBy('date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(50);
                             
        // カテゴリデータを取得（例）
        $categories = [
            '食事',
            '運動',
            '勉強',
            '仕事',
            '趣味',
            'その他'
        ];
        
        // 月別サマリーを計算
        $monthlySummary = null;
        
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        
        // 月別のデータを取得
        $monthlyRecords = DailyRecord::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();
        
        if ($monthlyRecords->isNotEmpty()) {
            // 収入と支出を計算
            $totalIncome = $monthlyRecords->where('amount', '>', 0)->sum('amount');
            $totalExpense = $monthlyRecords->where('amount', '<', 0)->sum('amount');
            $balance = $totalIncome + $totalExpense; // 支出は既に負の値
            
            // 月別サマリー (フィルタ条件適用)
            $monthlySummary = (object) [
                'total_records' => $monthlyRecords->count(),
                'record_count' => $monthlyRecords->count(),
                'total_income' => $totalIncome,
                'total_expense' => abs($totalExpense), // 表示用に正の値に変換
                'balance' => $balance,
                'categories' => $monthlyRecords->groupBy('category')->map(function ($records) {
                    return $records->count();
                }),
                'average_per_day' => round($monthlyRecords->count() / $startOfMonth->daysInMonth, 1),
                'most_active_day' => $monthlyRecords->groupBy('date')->sortByDesc(function ($records) {
                    return $records->count();
                })->keys()->first(),
            ];
        }
                                   
        // その他のデータ取得処理...
        $dailyRecords = DailyRecord::query();
        
        if ($request->filled('category')) {
            $dailyRecords->where('category', $request->category);
        }
        
        if ($request->filled('year')) {
            $dailyRecords->whereYear('date', $request->year);
        }
        
        if ($request->filled('month')) {
            $dailyRecords->whereMonth('date', $request->month);
        }
        
        $dailyRecords = $dailyRecords->orderBy('date', 'desc')->get();
        
        // カテゴリ別集計（当月）
        $categoryStats = DailyRecord::byMonth($year, $month)
                                   ->selectRaw('category, 
                                              SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as income,
                                              SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as expense')
                                   ->groupBy('category')
                                   ->get();
                                   
        $budgetGoal = BudgetGoal::where('user_id', $user->id)
            ->whereYear('year', $year)
            ->first();                                   
        
        return view('daily_records.index', compact('records', 'categories', 'monthlySummary', 'dailyRecords', 'categoryStats', 'year', 'month', 'budgetGoal'));
    }
    
    public function create()
    {
        $categories = [
            '食費', '交通費', '娯楽', '住居費', '光熱費', 'その他'
        ];
        
        return view('daily_records.create', [
            'categories' => $categories,
            'budgetGoal' => BudgetGoal::firstOrNew([
                'user_id' => auth()->id(),
                'year' => now()->year
            ])
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'memo' => 'nullable|string|max:500',
        ]);
        
        DailyRecord::create($validated);
        
        return redirect()->route('daily_records.index')
                        ->with('success', '記録を追加しました');
    }
    
    public function edit(DailyRecord $record)
    {   
        return view('daily_records.edit', [
            'record' => $record,
            'budgetGoal' => BudgetGoal::firstOrNew([
                'user_id' => auth()->id(),
                'year' => now()->year
            ])
        ]);
    }
    
    public function update(Request $request, DailyRecord $dailyRecord)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'memo' => 'nullable|string|max:500',
        ]);
        
        $dailyRecord->update($validated);
        
        return redirect()->route('daily_records.index')
                        ->with('success', '記録を更新しました');
    }
    
    public function destroy(DailyRecord $dailyRecord)
    {
        $dailyRecord->delete();
        
        return redirect()->route('daily_records.index')
                        ->with('success', '記録を削除しました');
    }
    
    // AJAX APIエンドポイント（モバイル対応）
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'memo' => 'nullable|string|max:500',
        ]);
        
        $record = DailyRecord::create($validated);
        
        return response()->json([
            'success' => true,
            'record' => $record,
            'message' => '記録を追加しました'
        ]);
    }
}
