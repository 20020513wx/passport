<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('/web')->middleware('checklogin')->group(function(){
    Route::get('/login','Web\LoginController@loginView');       // 登录
    Route::post('/login','Web\LoginController@login');       // 登录
     Route::any('/check/token','Web\LoginController@checkToken');//验证token

});