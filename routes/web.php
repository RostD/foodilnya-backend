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
Route::get('/product/{id}', 'Index@product');
Route::get('/attribute/{id}', 'Index@showAttribute');


Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/403', function () {
    return view('errors.403');
});

Route::group(['prefix' => 'ctrl', 'middleware' => ['auth', 'ctrl']], function () {

    Route::get('/', function () {
        return view('control.index');
    });

    Route::group(['prefix' => 'sys', 'middleware' => 'admin'], function () {

        Route::get('/', function () {
            return redirect('/ctrl/sys/directories');
        });
        Route::get('directories', function () {
            return view('control.system.directories');
        });

        Route::get('attributes', 'Control\AttributeController@attributes');
        Route::get('attribute/add', 'Control\AttributeController@formAdd');
        Route::post('attribute/add', 'Control\AttributeController@add');
        Route::get('attribute/{id}/edit', 'Control\AttributeController@formEdit');
        Route::put('attribute/{id}', 'Control\AttributeController@edit');
        Route::delete('attribute/{id}', 'Control\AttributeController@destroy');

        Route::get('units', 'Control\UnitController@units');
        Route::get('unit/add', 'Control\UnitController@formAdd');
        Route::post('unit/add', 'Control\UnitController@add');
        Route::delete('unit/{id}', 'Control\UnitController@destroy');
        Route::get('unit/{id}/edit', 'Control\UnitController@formEdit');
        Route::put('unit/{id}', 'Control\UnitController@edit');
    });

    Route::group(['prefix' => 'nmcl'], function () {

        Route::get('dishes', 'Control\DishController@dishes');
        Route::get('dish/add', 'Control\DishController@formAdd');
        Route::post('dish/add', 'Control\DishController@add');
        Route::get('dish/{id}/edit', 'Control\DishController@formEdit');
        Route::put('dish/{id}', 'Control\DishController@edit');
        Route::delete('dish/{id}', 'Control\DishController@delete');

        Route::get('ingredients', 'Control\IngredientController@ingredients');
        Route::get('ingredient/add', 'Control\IngredientController@formAdd');
        Route::post('ingredient/add', 'Control\IngredientController@add');
        Route::get('ingredient/{id}/edit', 'Control\IngredientController@formEdit');
        Route::put('ingredient/{id}', 'Control\IngredientController@edit');
        Route::delete('ingredient/{id}', 'Control\IngredientController@delete');
    });
});


