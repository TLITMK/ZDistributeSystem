<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeLevelBunusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_level_bunus', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_id')->comment('票档id');
            $table->string('level');
            $table->decimal('bunus',8,3)->comment('分红比例');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_bunus');
    }
}
