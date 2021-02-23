<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //用于保存某场次座位的颜色、状态等；
        Schema::create('seats_grades', function (Blueprint $table) {
            $table->id();
            $table->integer('seat_id')->comment('座位id');
            $table->integer('grade_id')->comment('票档id');
            $table->integer('status')->comment('');
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
        Schema::dropIfExists('seats_grades');
    }
}
