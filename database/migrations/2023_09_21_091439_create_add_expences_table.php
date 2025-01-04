<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddExpencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_expences', function (Blueprint $table) {
            $table->id();
            $table->integer('asset_id')->nullable()->default(NULL);
            $table->integer('type_id')->nullable()->default(NULL);
            $table->text('total_milage')->nullable()->default(NULL);
            $table->text('amount')->nullable()->default(NULL);
            $table->string('image')->nullable()->default(NULL);
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
        Schema::dropIfExists('add_expences');
    }
}
