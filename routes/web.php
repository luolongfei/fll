<?php

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

/**
 * pages
 */
Route::get('/', 'HomeController@index');
Route::get('/about', 'HomeController@about');

/**
 * api
 */
Route::middleware(['apiLogger'])->group(function () {
    Route::prefix('api')->group(function () {
        Route::post('price/get', ['uses' => 'Api\PriceController@getByApp']);
        Route::post('mail/idea', ['uses' => 'Api\Mail@idea']);
    });
});