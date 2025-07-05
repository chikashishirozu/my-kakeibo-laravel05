<?php

// tests/Unit/BudgetGoalTest.php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\BudgetGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetGoalTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_budget_goal_creation()
    {
        $user = User::factory()->create();
        $budgetGoal = BudgetGoal::create([
            'user_id' => $user->id,
            'year' => 2025,
            'annual_savings_target' => 1000000, // 100万円
        ]);
        
        $this->assertDatabaseHas('budget_goals', [
            'user_id' => $user->id,
            'year' => 2025,
            'annual_savings_target' => 1000000,
        ]);
    }
}
