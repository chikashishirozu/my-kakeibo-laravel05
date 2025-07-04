<?php

// database/migrations/2025_01_01_000001_create_budget_goals_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetGoalsTable extends Migration
{
    public function up()
    {
        Schema::create('budget_goals', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->decimal('annual_savings_target', 10, 2);
            $table->timestamps();
            
            $table->unique('year');
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_goals');
    }
}

