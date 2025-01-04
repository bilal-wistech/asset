<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCheckoutAssetUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkout_asset_user', function (Blueprint $table) {
            //
            $table->text('start_km')->nullable()->default(NULL)->after('handover_to');
            $table->text('end_km')->nullable()->default(NULL)->after('start_km');
            $table->text('total_milage')->nullable()->default(NULL)->after('end_km');
            $table->string('asset_right_pic')->nullable()->default(NULL)->after('total_milage');
            $table->string('asset_left_pic')->nullable()->default(NULL)->after('asset_right_pic');
            $table->string('asset_front_pic')->nullable()->default(NULL)->after('asset_left_pic');
            $table->string('asset_back_pic')->nullable()->default(NULL)->after('asset_front_pic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checkout_asset_user', function (Blueprint $table) {
            //
        });
    }
}
