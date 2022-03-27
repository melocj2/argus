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

Route::patch('users/{user}', 'Api\UserController@update')->name('users.update');


//sensor data
Route::post('sensors', 'Api\SensorController@store')->name('sensors.store');

Route::get('sensors', 'Api\SensorController@index')->name('sensors.index');

Route::patch('sensors/compress', 'Api\SensorController@compress')->name('sensors.compress');
