<?php

use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware'=>'api',
    'namespace'=>'App\Http\Controllers',
    'prefix' => 'admin'

],function(){
    Route::apiResource('users', 'UsersController');
    Route::apiResource('products', 'ProductsController');
    Route::apiResource('orders', 'OrdersController');
});


Route::group([
    'middleware'=>'api',
    'namespace'=>'App\Http\Controllers',
    'prefix' => 'public'
],function(){
    Route::apiResource('products', 'ProductsController')
        ->only('index', 'show');
    Route::apiResource('orders', 'OrdersController')
        ->only('store');
});
