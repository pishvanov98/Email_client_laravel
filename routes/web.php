<?php

use Illuminate\Support\Facades\Route;

Route::get('/set', 'EmailController@set')->name('set');//установить в очередь отправки почты
Route::get('/test', 'EmailController@test')->name('test');
Route::get('/send', 'EmailController@sendEmail')->name('send');

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'Admin', 'middleware' => ['role:admin'], 'prefix'=> 'admin'], function(){//prefix подставляет admin во всё что внутри группы в пути , namespace группа контрорреров в папке Admin middleware дал доступ роли админу

    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('/view/create', 'AdminController@create')->name('admin.view_create');
    Route::post('/view','AdminController@store')->name('admin.view_store');

});
