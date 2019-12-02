<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('clientes/store', 'ClienteController@store');
Route::get('clientes', 'ClienteController@index');

Route::post('habitaciones/store', 'HabitacionController@store');
Route::get('habitaciones', 'HabitacionController@index');
Route::get('habitaciones-disponibles', 'HabitacionController@habitacionesDisponibles');


Route::post('reservas/store', 'ReservaController@store');
Route::get('reservas', 'ReservaController@index');
