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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/ingredient/{id}/availableUnits', 'Api\IngredientController@getAvailableUnits');
Route::get('/component/{id}/availableUnits', 'Api\DishComponentController@getAvailableUnits');
Route::get('/material/{id}/availableUnits', 'Api\MaterialController@getAvailableUnits');
Route::get('/attribute/{id}', 'Api\AttributeController@getAttribute');
Route::get('/client/{id}/address', 'Api\ClientController@getAddress');

Route::resource('dishes', 'Api\DishController',
    ['only' => ['index', 'show']]);

Route::get('usedTags/{option?}', 'Api\TagsController@getUsed');
