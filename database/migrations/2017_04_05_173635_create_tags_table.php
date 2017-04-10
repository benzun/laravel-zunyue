<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_users_id')->unsigned()->default(0)->comment('管理帐号ID');
            $table->foreign('admin_users_id')->references('id')->on('admin_users');
            $table->integer('account_id')->unsigned()->default(0)->comment('所属公众号账号ID');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->integer('tag_id')->unsigned()->default(0)->comment('标签ID');
            $table->string('name',45)->default('')->comment('名称');
            $table->integer('count')->unsigned()->default(0)->comment('用户数量');
            $table->enum('built_in',['yes','no'])->default('no')->comment('是否系统内置');
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
        Schema::drop('tags');
    }
}
