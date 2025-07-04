<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/2025_01_01_000003_create_daily_records_table.php
class CreateDailyRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('category', 50); // 食費、交通費、給与など
            $table->string('item', 100); // 具体的な項目名
            $table->decimal('amount', 10, 2); // 正数=収入、負数=支出
            $table->text('memo')->nullable();
            $table->timestamps();
            
            $table->index(['date', 'category']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_records');
    }
}
