<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //座位
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->integer('theatre_id')->comment('剧场id');
            $table->string('area')->comment('区');
            $table->integer('row_num')->comment('排');
            $table->integer('seat_num')->comment('座号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
