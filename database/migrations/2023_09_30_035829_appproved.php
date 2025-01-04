<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Appproved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_expences', function (Blueprint $table) {
            $table->boolean('approved')->default(0);
            $table->boolean('disapproved')->default(0);
            $table->boolean('paid')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_expences', function (Blueprint $table) {
            //
        });
    }
}
