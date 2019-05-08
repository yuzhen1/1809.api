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
Route::get('/phpinfo', function () {
    return view('phpinfo');
});
//
Route::get('/user/addUser','UserController@addUser');
//post提交方式
Route::get('/user/threePost','UserController@threePost');
Route::get('/user/threePost2','UserController@threePost2');
Route::get('/user/threePost3','UserController@threePost3');

//请求中间件的方法
Route::get('/user/mid','UserController@mid')->middleware('filter10');

//注册
Route::post('/user/register','UserController@register');
Route::post('/user/login','UserController@login');
//登录
Route::get('/user/mid','UserController@mid')->middleware('checkLoginToken');

//个人中心
Route::get('/user/myCenter','UserController@myCenter')->middleware('checkLogin');

//资源控制器路由
Route::resource('/goods', GoodsController::class);