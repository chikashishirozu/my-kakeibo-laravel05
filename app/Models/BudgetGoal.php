<?php

// app/Models/BudgetGoal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; 

class BudgetGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'annual_savings_target',
    ];
    
    
    protected $casts = [
        'annual_savings_target' => 'decimal:2',
    ];

    /**
     * 現在年の目標を取得
     */
    public static function getCurrentYearGoal()
    {
        $user = Auth::user();
        $currentYear = date('Y');
        
        return self::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => $currentYear
            ],
            [
                'annual_savings_target' => 0
            ]
        );
    }

    /**
     * 特定年の目標を取得
     */
    public static function getYearGoal($year)
    {
        $user = Auth::user();
        
        return self::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => $year
            ],
            [
                'annual_savings_target' => 0
            ]
        );
    }
    

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
