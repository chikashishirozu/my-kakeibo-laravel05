<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\DailyRecordsController;
use App\Http\Controllers\BudgetGoalsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| 認証が不要なルート
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| 認証が必要なルート
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // ログアウト
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // ダッシュボード（ホーム画面）
    Route::get('/dashboard', [DashboardsController::class, 'index'])->name('dashboard');
    
    // 日別記録管理
    Route::resource('daily-records', DailyRecordsController::class, [
        'names' => [
            'index' => 'daily_records.index',
            'create' => 'daily_records.create',
            'store' => 'daily_records.store',
            'edit' => 'daily_records.edit',
            'update' => 'daily_records.update',
            'destroy' => 'daily_records.destroy',
        ]
    ])
        ->parameters(['daily-records' => 'record']);
    
    // 年間目標設定
    Route::resource('/budget_goals', BudgetGoalsController::class, [
        'names' => [
            'edit' => 'budget_goals.edit',
            'update' => 'budget_goals.update',
        ],
        'only' => ['edit', 'update']
    ]);

    // AJAX用APIエンドポイント
    Route::prefix('api')->group(function () {
        Route::post('/daily-records', [DailyRecordController::class, 'apiStore']);
        Route::get('/dashboard-data', [DashboardController::class, 'apiData']);
    });
});

/*
業界豆知識：
- middleware(['auth'])を使うことで認証が必要なルートをグループ化
- Laravel Breezeやjetstream等のスターターキットを使うのが一般的
- CSRFトークンは自動で検証される
- ルートキャッシュ(route:cache)でパフォーマンス向上可能
*/
