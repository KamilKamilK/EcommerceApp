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
    Route::resource('users', 'UsersController');
    Route::resource('products', 'ProductsController');
});


Route::group([
    'middleware'=>'api',
    'namespace'=>'App\Http\Controllers',
    'prefix' => 'public'
],function(){
    Route::resource('products', 'ProductsController')
        ->only('index', 'show');

});
