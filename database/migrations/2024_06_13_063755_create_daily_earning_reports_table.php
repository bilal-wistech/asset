<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyEarningReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_earning_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('courier_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('phone',20)->nullable();
            $table->string('city')->nullable();
            $table->string('offline',20)->nullable();
            $table->integer('days_since_last_delivery')->nullable();
            $table->integer('days_since_last_offload')->nullable();
            $table->string('earnings_without_tips_yesterday',25)->nullable();
            $table->integer('hours_online_yesterday')->nullable();
            $table->integer('hours_on_task_yesterday')->nullable();
            $table->integer('cash_balance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_earning_reports');
    }
}
