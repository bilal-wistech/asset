<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance', function (Blueprint $table) {
            $table->id();
            $table->string('asset')->nullable()->default(NULL);
            $table->string('vendor')->nullable()->default(NULL);
            $table->datetime('insurance')->nullable()->default(NULL);
            $table->datetime('insurance_from')->nullable()->default(NULL);
            $table->datetime('insurance_to')->nullable()->default(NULL);
            $table->integer('amount')->nullable()->default(NULL);
            $table->string('premium')->nullable()->default(NULL);
            $table->integer('cost')->nullable()->default(NULL);
            $table->integer('no_of_drivers_allowed')->nullable()->default(NULL);
            $table->integer('driver_cost')->nullable()->default(NULL);
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
        Schema::dropIfExists('insurance');
    }
}
