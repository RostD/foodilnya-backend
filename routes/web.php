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

use App\MaterialValue\Property;
use App\Models\TypeOfMaterialValue;

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

        Route::get('directories', function () {
            return view('control.system.directories');
        });
        Route::get('attributes', function () {
            $data['properties'] = Property::all();
            return view('control.system.attributes', $data);
        });
        Route::get('attribute/{id}', function ($id) {
            $data['property'] = Property::find($id);
            $data['types'] = TypeOfMaterialValue::all();
            if (!$data['property'])
                return view('errors/404');
            return view('control.system.forms.attribute', $data);
        });

        Route::put('attribute/{id}', function () {
            //TODO: Изменение аттрибута
        });
    });
});


