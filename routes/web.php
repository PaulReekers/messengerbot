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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhook', 'BotController@receive');
Route::get('/webhook', 'BotController@check');

Route::post('api/v1/question','MessageController@storeQuestion');
Route::post('api/v1/question/{id}','MessageController@storeQuestion');
Route::post('api/v1/question/{id}/option','MessageController@storeOptionQuestion');
Route::post('api/v1/question/{id}/option/{option}','MessageController@storeOptionQuestion');

Route::get('api/v1/questions','MessageController@all');
Route::get('api/v1/question','MessageController@getQuestion');
Route::get('api/v1/question/{id}','MessageController@getQuestion');

