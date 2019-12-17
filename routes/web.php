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

use Illuminate\Support\Facades\Route;

/**
 * pages
 */
Route::get('/', 'HomeController@index');
Route::get('/about', 'HomeController@about');
Route::get('/price/{urlCode?}', 'HomeController@price');

Route::get('/shaer520/copy/{token}', 'HomeController@copy');

/**
 * api
 */
Route::prefix('api')->group(function () {
    Route::post('price/get', ['uses' => 'Api\PriceController@getByApp']);
    Route::post('mail/idea', ['uses' => 'Api\Mail@idea']);
});
