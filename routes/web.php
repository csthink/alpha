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

// 静态页面路由
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('help', 'StaticPagesController@help')->name('help');
Route::get('about', 'StaticPagesController@about')->name('about');

// 渲染注册页面
Route::get('signup', 'UsersController@create')->name('signup');
// 定义资源路由，遵从 RESTful 架构
Route::resource('users', 'UsersController');

/*
 * 上面的资源路由代码等同于
Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
*/

// 渲染登录页面
Route::get('login', 'SessionsController@create')->name('login');
// 登录操作
Route::post('login', 'SessionsController@store')->name('login');
// 登出操作
Route::delete('logout', 'SessionsController@destroy')->name('logout');
