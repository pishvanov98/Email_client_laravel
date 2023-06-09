<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', 'EmailController@test')->name('test');
Route::get('/send', 'EmailController@sendEmail')->name('send');
Route::get('/redirect', 'EmailController@redirect')->name('redirect');
Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'Admin', 'middleware' => ['role:admin'], 'prefix'=> 'admin'], function(){//prefix подставляет admin во всё что внутри группы в пути , namespace группа контрорреров в папке Admin middleware дал доступ роли админу

    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('/view/create', 'AdminController@create')->name('admin.view_create');
    Route::get('/view/{id}/edit', 'AdminController@edit')->name('admin.view_edit');
    Route::put('/view/{id}', 'AdminController@update')->name('admin.view_update');
    Route::delete('/view/{id}','AdminController@destroy')->name('admin.view_destroy');
    Route::post('/view','AdminController@store')->name('admin.view_store');

});
