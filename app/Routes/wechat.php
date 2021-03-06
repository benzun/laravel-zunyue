<?php

/*
|--------------------------------------------------------------------------
| Wechat Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('service','WechatController@service');

Route::group(['middleware' => 'wechat-login'], function () {
    Route::controller('high-praise-cashback', 'HighPraiseCashbackController');
});