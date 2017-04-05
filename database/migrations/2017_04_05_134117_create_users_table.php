<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
            $table->increments('id');
            $table->integer('admin_users_id')->unsigned()->default(0)->comment('管理帐号ID');
            $table->foreign('admin_users_id')->references('id')->on('admin_users');
            $table->integer('account_id')->unsigned()->default(0)->comment('所属公众号账号ID');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->string('openid')->default('')->comment('用户的标识');
            $table->tinyInteger('subscribe')->default(0)->comment('用户是否订阅该公众号标识');
            $table->string('nickname')->default('')->comment('昵称');
            $table->tinyInteger('sex')->default('0')->comment('性别,0未知,1男性,2女');
            $table->string('city')->default('')->comment('城市');
            $table->string('province')->default('')->comment('省份');
            $table->string('headimgurl')->default('')->comment('头像');
            $table->integer('subscribe_time')->default(0)->comment('关注时间');
            $table->string('remark')->default('')->comment('备注');
            $table->string('unionid')->default('')->comment('unionid');
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
        Schema::drop('users');
    }
}
