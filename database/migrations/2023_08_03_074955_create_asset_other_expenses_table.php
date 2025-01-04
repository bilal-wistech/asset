<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetOtherExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_other_expenses', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable()->default(NULL);
            $table->string('receipt')->nullable()->default(NULL);
            $table->bigInteger('asset_id')->nullable()->default(NULL);
            $table->bigInteger('checkout_id')->nullable()->default(NULL);
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
        Schema::dropIfExists('asset_other_expenses');
    }
}
