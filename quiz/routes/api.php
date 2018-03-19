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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'UserController@logout');

    Route::get('test', function() {
        return response()->json(['foo' => 'bar']);
    });

    Route::prefix('category')->group(function() {
        Route::get('/', 'CategoryController@fetch');
        Route::get('/{id}', 'CategoryController@find');
        Route::post('/', 'CategoryController@insert');
        Route::put('/{id}', 'CategoryController@update');
        Route::delete('/{id}', 'CategoryController@delete');
    });

    Route::prefix('item')->group(function() {
        Route::get('/', 'ItemController@fetch');
        Route::get('/{id}', 'ItemController@find');
        Route::post('/', 'ItemController@insert');
        Route::put('/{id}', 'ItemController@update');
        Route::delete('/{id}', 'ItemController@delete');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
