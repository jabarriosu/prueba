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

Route::get('login', 'AuthController@frmLogin')->name('login');
Route::post('register', 'AuthController@register');
Route::post('tokens/store', 'AuthController@tokensStore');

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login')->name('api.login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout')->name('logout');

});

Route::group(['middleware' => ['api','role:Administrador'], 'prefix' => 'user'], function ($router) {
    Route::get('show', 'UserController@show')->name('user.show');
    Route::post('get', 'UserController@get')->name('user.get');
    Route::post('store', 'UserController@store')->name('user.store');
    Route::put('/{customer}/update', 'UserController@update')->name('user.update');
    Route::delete('/{customer}/delete', 'UserController@delete')->name('user.delete');

});

Route::group(['middleware' => 'api', 'prefix' => 'customer'], function ($router) {
    Route::get('show', 'CustomerController@show')->name('customer.show');
    Route::post('get', 'CustomerController@get')->name('customer.get');
    Route::post('store', 'CustomerController@store')->name('customer.store');
    Route::put('/{customer}/update', 'CustomerController@update')->name('customer.update');
    Route::delete('/{customer}/delete', 'CustomerController@delete')->name('customer.delete');

});

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
