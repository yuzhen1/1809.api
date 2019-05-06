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