<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\AuthController;

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
    // 'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth',
], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    
    Route::get('userList','AuthController@getUserList');
});

Route::get('login', function () {
    return response()->json(['message' => 'Unauthorized.'], 401);
})->name('login');

Route::group([
    // 'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth',
], function () {
    Route::get('logout', 'AuthController@logout')->middleware('auth:api');
    
});

Route::group([
    // 'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'message',
], function () {
    Route::get('/', 'MessageController@index')->middleware(['auth:api']);
    Route::post('/', 'MessageController@store')->middleware(['auth:api']);
    
});


Route::group([
    // 'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'feedback',
], function () {
    Route::get('/', 'FeedbackController@index')->middleware('auth:api');
    Route::post('/', 'FeedbackController@store')->middleware('auth:api');
    
});


Route::group([
    // 'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'customer',
], function () {
    Route::get('/', 'UserController@index')->middleware(['auth:api', 'staff']);
    Route::delete('/{id}', 'UserController@destroy')->middleware(['auth:api', 'staff']);
    
});