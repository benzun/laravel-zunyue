<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_users_id')->unsigned()->default(0)->comment('后台用户id');
            $table->foreign('admin_users_id')->references('id')->on('admin_users');
            $table->char('identity', 32)->default('')->unique()->comment('账号身份标识');
            $table->string('name', 45)->default('')->comment('名称');
            $table->char('original_id', 15)->default('')->comment('原始ID');
            $table->string('wechat_id', 45)->default('')->comment('微信号');
            $table->enum('type', ['subscribe', 'service', 'auth_subscribe', 'auth_service'])->default('subscribe')->comment('账号类型');
            $table->char('app_id', 18)->default('')->comment('appid');
            $table->char('secret', 32)->default('')->comment('secret');
            $table->char('token', 32)->default('')->comment('token令牌');
            $table->char('aes_key', 43)->default('')->comment('消息加解密密钥EncodingAESKey');
            $table->enum('activate', ['yes', 'no'])->default('no')->comment('是否在微信公众号服务器配置接入成功');
            $table->softDeletes();
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
        Schema::drop('accounts');
    }
}
