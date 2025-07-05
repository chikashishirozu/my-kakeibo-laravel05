<?php
// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailyRecordController;

/*
|--------------------------------------------------------------------------
| API Routes (RESTful API)
|--------------------------------------------------------------------------
|
| モバイルアプリや外部連携用のAPIエンドポイント
| 認証が必要な場合はSanctumを使用することを推奨
|
*/

Route::middleware('api')->group(function () {
    // 日別記録のCRUD
    Route::apiResource('daily-records', DailyRecordController::class);
    
    // ダッシュボードデータ
    Route::get('dashboard', [DashboardController::class, 'apiIndex']);
    
    // 月別サマリー
    Route::get('monthly-summary/{year}/{month}', function ($year, $month) {
        return \App\Models\MonthlySummary::where('year', $year)
                                        ->where('month', $month)
                                        ->first();
    });
    
    // カテゴリ別統計
    Route::get('category-stats/{year}/{month}', function ($year, $month) {
        return \App\Models\DailyRecord::byMonth($year, $month)
                                     ->selectRaw('category, 
                                                SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as income,
                                                SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as expense,
                                                COUNT(*) as count')
                                     ->groupBy('category')
                                     ->get();
    });
});

// 認証が必要なAPIルート（Laravel Sanctum使用）
Route::middleware(['auth:sanctum'])->group(function () {
    // ユーザー情報取得
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // プロテクトされたエンドポイント
    Route::apiResource('protected/daily-records', DailyRecordController::class);
});
