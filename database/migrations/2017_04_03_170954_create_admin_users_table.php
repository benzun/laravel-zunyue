<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile', 20)->default(0)->unique()->comment('手机号码');
            $table->string('password')->default('')->comment('密码');
            $table->string('nickname',20)->default('')->comment('昵称');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态,0禁用,1正常');
            $table->rememberToken();
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
        Schema::drop('admin_users');
    }
}
