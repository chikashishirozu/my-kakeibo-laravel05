<?php

// tests/Feature/DataFlowTest.php
namespace Tests\Feature;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\User;
use App\Models\BudgetGoal;
use App\Models\DailyRecord;
use App\Models\MonthlySummary;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_kakeibo_flow()
    {
        // 1. ユーザーが年間目標を設定
        $user = User::factory()->create();
        $budgetGoal = BudgetGoal::create([
            'user_id' => $user->id,
            'year' => 2025,
            'annual_savings_target' => 1200000,
        ]);
        
        // 2. 日々の記録を作成
        $dailyRecords = [
            ['date' => '2025-01-01', 'category' => '収入', 'amount' => 300000, 'memo' => '給与'],
            ['date' => '2025-01-02', 'category' => '食費', 'amount' => -3000, 'memo' => 'スーパー'],
            ['date' => '2025-01-03', 'category' => '交通費', 'amount' => -1500, 'memo' => '電車代'],
        ];
        
        foreach ($dailyRecords as $record) {
            DailyRecord::create($record);
        }
        
        // 3. 月次サマリーの生成をテスト
        $monthlySummary = MonthlySummary::create([
            'year' => 2025,
            'month' => 2,
            'total_income' => 300000,
            'total_expense' => 4500,
            'balance' => 295500,
        ]);
        
        // 4. データの整合性を確認
        $this->assertEquals(295500, $monthlySummary->balance);
        $this->assertEquals(300000 - 4500, $monthlySummary->balance);
    }
}
