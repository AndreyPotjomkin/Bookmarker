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

Route::get('/', ['as' => 'bookmark-create', 'uses' => 'BookmarkController@index']);

Route::get('/bookmarks/create', ['as' => 'bookmark-create', 'uses' => 'BookmarkController@create']);

Route::post('/bookmarks/create', ['as' => 'bookmark-store', 'uses' => 'BookmarkController@store']);

Route::get('/bookmarks/{id}', ['as' => 'bookmark-show', 'uses' => 'BookmarkController@show']);

Route::delete('/bookmarks/{id}', ['as' => 'bookmark-delete', 'uses' => 'BookmarkController@destroy']);

Route::get('/get-excel', 'BookmarkController@downloadExcel');
