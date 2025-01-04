<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutAssetUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_asset_user', function (Blueprint $table) {
            $table->id();
            $table->integer('asset_id')->nullable()->default(NULL);
            $table->datetime('checkout_date')->nullable()->default(NULL);
            $table->datetime('expected_checkin_date')->nullable()->default(NULL);
            $table->datetime('checkin_date')->nullable()->default(NULL);
            $table->text('note')->nullable()->default(NULL);

            $table->timestamps();
            $table->datetime('deleted_at')->nullable()->default(NULL);
            $table->integer('created_by')->nullable()->default(NULL);
            $table->integer('updated_by')->nullable()->default(NULL);
            $table->integer('deleted_by')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkout_asset_user');
    }
}
