<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheatreAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //剧场区域
        Schema::create('theatre_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('theatre_id')->comment('剧场id');
            $table->string('title')->comment('区域名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theatre_areas');
    }
}
