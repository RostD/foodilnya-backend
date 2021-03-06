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

        Route::get('adaptations', 'Control\AdaptationController@adaptations');
        Route::get('adaptation/add', 'Control\AdaptationController@formAdd');
        Route::post('adaptation/add', 'Control\AdaptationController@add');
        Route::get('adaptation/{id}/edit', 'Control\AdaptationController@formEdit');
        Route::put('adaptation/{id}', 'Control\AdaptationController@edit');
        Route::delete('adaptation/{id}', 'Control\AdaptationController@delete');

        Route::get('products', 'Control\ProductController@products');
        Route::get('product/add', 'Control\ProductController@formAdd');
        Route::post('product/add', 'Control\ProductController@add');
        Route::get('product/{id}/edit', 'Control\ProductController@formEdit');
        Route::put('product/{id}', 'Control\ProductController@edit');
        Route::delete('product/{id}', 'Control\ProductController@delete');

        Route::group(['prefix' => 'cfg'], function () {

            Route::get('dish/{id}', 'Control\DishController@constructor');

            Route::get('dish/{id}/addIngredient', 'Control\DishController@formAddIngredient');
            Route::post('dish/{id}/addIngredient', 'Control\DishController@addIngredient');
            Route::delete('dish/{d_id}/ingredient/{i_id}', 'Control\DishController@removeIngredient');
            Route::get('dish/{d_id}/ingredient/{i_id}', 'Control\DishController@formEditIngredient');
            Route::put('dish/{id}/editIngredient', 'Control\DishController@editIngredient');

            Route::post('dish/{id}/recipe', 'Control\DishController@setRecipe');

            Route::get('dish/{id}/addAdaptation', 'Control\DishController@formAddAdaptation');
            Route::post('dish/{id}/addAdaptation', 'Control\DishController@addAdaptation');
            Route::delete('dish/{d_id}/adaptation/{i_id}', 'Control\DishController@removeAdaptation');
            Route::get('dish/{d_id}/adaptation/{i_id}', 'Control\DishController@formEditAdaptation');
            Route::put('dish/{id}/editAdaptation', 'Control\DishController@editAdaptation');

            Route::get('dish/{id}/addAttribute', 'Control\DishController@formAddAttribute');
            Route::post('dish/{id}/addAttribute', 'Control\DishController@addAttribute');
            Route::delete('dish/{d_id}/attribute/{i_id}', 'Control\DishController@removeAttribute');


            Route::get('product/{id}', 'Control\ProductController@constructor');

            Route::get('product/{id}/addComponent', 'Control\ProductController@formAddComponent');
            Route::post('product/{id}/addComponent', 'Control\ProductController@addComponent');
            Route::delete('product/{d_id}/component/{i_id}', 'Control\ProductController@removeComponent');


        });

    });

    Route::group(['prefix' => 'order'], function () {

        Route::get('clients', 'Control\ClientController@clients');
        Route::get('client/add', 'Control\ClientController@formAdd');
        Route::post('client/add', 'Control\ClientController@add');
        Route::get('client/{id}/edit', 'Control\ClientController@formEdit');
        Route::put('client/{id}', 'Control\ClientController@edit');
        Route::delete('client/{id}', 'Control\ClientController@delete');

        Route::get('orders', 'Control\OrderController@orders');
        Route::get('order/add', 'Control\OrderController@formAdd');
        Route::post('order/add', 'Control\OrderController@add');
        Route::get('order/{id}/edit', 'Control\OrderController@formEdit');
        Route::put('order/{id}', 'Control\OrderController@edit');
        Route::delete('order/{id}', 'Control\OrderController@delete');
        Route::get('order/{id}/addMaterialStringDish', 'Control\OrderController@formAddMaterialStringDish');
        Route::get('order/{id}/addMaterialStringIngredient', 'Control\OrderController@formAddMaterialStringIngredient');
        Route::get('order/{id}/addMaterialStringAdaptation', 'Control\OrderController@formAddMaterialStringAdaptation');
        Route::post('order/{id}/addMaterialString', 'Control\OrderController@addMaterialString');
        Route::get('order/{d_id}/material/{i_id}', 'Control\OrderController@formEditMaterialString');
        Route::put('order/{id}/editMaterialString', 'Control\OrderController@editMaterialString');
        Route::delete('order/{d_id}/material/{i_id}', 'Control\OrderController@removeMaterialString');

    });

    Route::group(['prefix' => 'warehouse'], function () {

        Route::get('base/balance', 'Control\WarehouseController@balanceWHBase');

        Route::get('ordersForPicking', 'Control\WarehouseController@ordersForPicking');
        Route::get('base/plannedCosts', 'Control\WarehouseController@plannedCosts');

    });

});


