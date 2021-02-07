<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('account')->unique()->comment('微信小程序用户名 未绑定手机默认为openid 绑定手机后设置为手机号');
            $table->string('password');
            $table->string('nickname', 32)->comment('昵称');
            $table->unsignedTinyInteger('gender')->default(3)->comment('性别 1 男 2 女 3 未知');
            $table->tinyInteger('developer')->default(0)->comment('超管开发者 1是 0不是');
            $table->string('avatar', 128)->nullable();
            $table->string('email')->unique();
            $table->string('signature')->nullable();
            $table->string('open_id')->nullable();//用于小程序用户记录open_id，访问时触发新增用户，？？更新？？用户服务端查找redis中的session_key和小程序登陆的自定义唯一密钥
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
