<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //演出
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('标题');
            $table->integer('theatre_id')->comment('剧场id');
            $table->string('cover_image')->comment('封面图url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performances');
    }
}
