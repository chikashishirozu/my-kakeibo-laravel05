<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Dashboard
{
    public static function getMonthlySummary($userId, $year, $month = null)
    {
        $query = DailyRecord::where('user_id', $userId)
                  ->whereYear('date', $year);
        
        if ($month) {
            $query->whereMonth('date', $month);
        }
        
        return $query->selectRaw('
            SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END) as expense,
            SUM(amount) as balance,
            COUNT(*) as record_count
        ')->first();
    }
    
    public static function getYearlyTrend($userId, $year)
    {
        return DailyRecord::where('user_id', $userId)
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->selectRaw('
                MONTH(date) as month,
                SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END) as expense,
                SUM(amount) as balance
            ')
            ->orderBy('month')
            ->get();
    }
}
