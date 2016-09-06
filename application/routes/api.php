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

Route::group(['middleware' => 'api'], function () {
    Route::get('/', function ()    {
        // Uses Api Middleware
    });

    Route::resource('payment', 'PaymentController', ['only' => [
        'index', 'show'
    ]]);

    Route::resource('physician', 'PhysicianController', ['only' => [
        'index', 'show'
    ]]);
});