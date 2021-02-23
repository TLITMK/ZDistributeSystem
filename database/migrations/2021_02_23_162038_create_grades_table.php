<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //票档（商品）
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->decimal('price')->comment('售价');
            $table->decimal('total_bunus',8,3)->comment('总分红比例');
            $table->integer('session_id')->comment('场次id');
            $table->integer('performance_id')->comment('演出id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
