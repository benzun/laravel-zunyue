<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('login', 'Auth\LoginController@getLogin');
Route::post('login', 'Auth\LoginController@postLogin');
Route::get('logout', 'Auth\LoginController@getLogout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@getRegister');
Route::post('register', 'Auth\RegisterController@postRegister');

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/', 'IndexController@index');
    Route::controller('account', 'AccountController');

    Route::group(['middleware' => 'has-wechat-account'], function () {
        Route::controller('user', 'UsersController');
    });
});