<?php

// app/Http/Controllers/BudgetGoalController.php
namespace App\Http\Controllers;

use App\Models\BudgetGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class BudgetGoalsController extends Controller
{
    public function edit($id)
    {   
        $budgetGoal = BudgetGoal::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        return view('budget_goals.edit', compact('budgetGoal'));
    }
/*    
    public function index()
    {
        $budgetGoals = BudgetGoal::where('user_id', Auth::id())->get();
        return view('budget_goals.index', compact('budgetGoals'));
    }    
*/   
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'annual_savings_target' => 'required|numeric|min:0',
        ]);
        
        // ユーザー自身の目標のみ更新可能にする
        $budgetGoal = BudgetGoal::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $budgetGoal->update($validated);
        
        return redirect()->route('dashboard')
                        ->with('success', '目標を更新しました');        
    }
}
