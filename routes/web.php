<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // ダッシュボード
    Route::get('/', 'IndexController@index')->name('dashboard');

    Route::get('add', 'IndexController@add')->name('add');
    Route::post('add', 'IndexController@create');

    Route::get('edit/{id}', 'IndexController@edit')->name('edit');
    Route::post('edit/{id}', 'IndexController@update');

    // 注文書の削除
    Route::get('deleteOrder', 'IndexController@deleteOrder')->name('deleteOrder');
    // 工事の削除
    Route::get('delete/{id}', 'IndexController@delete')->name('delete');
    Route::post('delete/{id}', 'IndexController@destroy');
});
