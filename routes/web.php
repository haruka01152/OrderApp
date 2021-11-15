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

    Route::get('add/{date?}', 'ViewController@add')->name('add');
    Route::post('add/{date?}', 'IndexController@create');

    Route::get('edit/{id}', 'ViewController@edit')->name('edit');
    Route::post('edit/{id}', 'IndexController@update');

    // 注文書の削除
    Route::get('deleteOrder', 'OrderController@deleteOrder')->name('deleteOrder');
    // 工事の削除
    Route::get('delete/{id}', 'ViewController@delete')->name('delete');
    Route::post('delete/{id}', 'IndexController@destroy');

    Route::post('restore/{id}', 'IndexController@restore')->name('restore');

    Route::get('calender', 'ViewController@calender')->name('calender');

    Route::get('alerts', 'ViewController@alerts')->name('alerts');

    Route::get('logs', 'ViewController@logs')->name('logs');

    // 開発用ルート（運用中はコメントアウト）
    Route::get('createAlerts', 'AlertController@create');
    Route::get('destroyAlerts', 'AlertController@delete');
});
