<?php

use Illuminate\Support\Facades\Route;

Route::get('/set', 'EmailController@set')->name('set');//установить в очередь отправки почты
Route::get('/test', 'EmailController@test')->name('test');

