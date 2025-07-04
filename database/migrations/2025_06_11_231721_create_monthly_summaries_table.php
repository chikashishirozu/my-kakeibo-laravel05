<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2025_01_01_000002_create_monthly_summaries_table.php
class CreateMonthlySummariesTable extends Migration
{
    public function up()
    {
        Schema::create('monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->decimal('total_income', 10, 2)->default(0);
            $table->decimal('total_expense', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['year', 'month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_summaries');
    }
}

