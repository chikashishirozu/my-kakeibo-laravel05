<?php

// app/Models/DailyRecord.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyRecord extends Model
{
    protected $fillable = ['user_id', 'date', 'category', 'amount', 'memo'];
    
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
    
    // 自動で月次サマリーを更新するイベント
    protected static function booted()
    {
        static::created(function ($record) {
            static::updateMonthlySummary($record->date);
        });
        
        static::updated(function ($record) {
            static::updateMonthlySummary($record->date);
            // 日付が変更された場合は元の月も更新
            if ($record->wasChanged('date')) {
                static::updateMonthlySummary($record->getOriginal('date'));
            }
        });
        
        static::deleted(function ($record) {
            static::updateMonthlySummary($record->date);
        });
    }
    
    private static function updateMonthlySummary($date)
    {
        $carbon = Carbon::parse($date);
        MonthlySummary::updateSummary($carbon->year, $carbon->month);
    }
    
    public function scopeIncome($query)
    {
        return $query->where('amount', '>', 0);
    }
    
    public function scopeExpense($query)
    {
        return $query->where('amount', '<', 0);
    }
    
    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }
}
