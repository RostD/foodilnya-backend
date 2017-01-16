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
Route::get('/attribute/{id}', 'Index@showAttribute');

Route::get('/control', 'Control\Pages@index');
Route::get('/control/meals', 'Control\Pages@meals');
