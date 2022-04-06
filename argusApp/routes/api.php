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

Route::get('sensors/addNew', 'Api\SensorController@addNew')->name('sensors.addNew');

//plant data
Route::get('plants/{user}', 'Api\PlantController@getPlantList')->name('plants.getPlantList');

Route::post('plants', 'Api\PlantController@store')->name('plants.store');

Route::patch('plants/{id}', 'Api\PlantController@update')->name('plants.update');

Route::delete('plants/{id}', 'Api\PlantController@destroy')->name('plants.destory');

//message data
Route::get('messages/{user}', 'Api\MessageController@getMessageList')->name('messages.getMessageList');

Route::patch('messages/{id}', 'Api\MessageController@update')->name('messages.update');

Route::post('messages/sendAlert', 'Api\MessageController@sendAlert')->name('messages.sendAlert');

//plant care tips
Route::group(['namespace' => 'Api'], function () {
    Route::get('get-tips', 'TipsController@index');
});
