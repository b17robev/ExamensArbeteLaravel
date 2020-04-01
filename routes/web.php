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

Route::get('/airports', 'AirportsController@index');
Route::post('/airports', 'AirportsController@store');
Route::get('/airports/{id}', 'AirportsController@show');
Route::patch('/airports/{id}', 'AirportsController@update');
Route::delete('/airports/{id}', 'AirportsController@destroy');
