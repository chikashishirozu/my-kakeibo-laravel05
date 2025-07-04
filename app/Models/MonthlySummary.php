<?php

// app/Models/MonthlySummary.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    protected $fillable = ['year', 'month', 'total_income', 'total_expense', 'balance'];
    
    protected $casts = [
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'balance' => 'decimal:2',
    ];
    
    public function dailyRecords()
    {
        return $this->hasMany(DailyRecord::class, 'date')
                   ->whereYear('date', $this->year)
                   ->whereMonth('date', $this->month);
    }
    
    public static function updateSummary($year, $month)
    {
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $income = DailyRecord::whereBetween('date', [$startDate, $endDate])
                            ->where('amount', '>', 0)
                            ->sum('amount');
        
        $expense = abs(DailyRecord::whereBetween('date', [$startDate, $endDate])
                                ->where('amount', '<', 0)
                                ->sum('amount'));
        
        return self::updateOrCreate(
            ['year' => $year, 'month' => $month],
            [
                'total_income' => $income,
                'total_expense' => $expense,
                'balance' => $income - $expense
            ]
        );
    }
}
