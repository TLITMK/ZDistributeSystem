<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheatersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //剧场
        Schema::create('theaters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('剧场名');
            $table->string('address')->comment('地址');
            $table->decimal('longitude')->comment('经度');
            $table->decimal('latitude')->comment('纬度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theaters');
    }
}
