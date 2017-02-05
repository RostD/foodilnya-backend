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

Route::get('/', 'Index@index');
Route::get('/material/{id}', 'Index@showMaterial');
Route::get('/ingredient/{id}', 'Index@ingredient');
Route::get('/adaptation/{id}', 'Index@adaptation');
Route::get('/dish/{id}', 'Index@dish');
Route::get('/attribute/{id}', 'Index@showAttribute');


Auth::routes();

Route::get('/home', 'HomeController@index');

