<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowedDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowed_drivers', function (Blueprint $table) {
            $table->id();
            $table->integer('insurance_id')->nullable()->default(NULL);
            $table->string('driver_name')->nullable()->default(NULL);
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
        Schema::dropIfExists('allowed_drivers');
    }
}
