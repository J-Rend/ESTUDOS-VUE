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

Route::get('/login', 'Auth\LoginController@defaultMessage');
Route::post('/login', 'Auth\LoginController@login');
                
                
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api']], function () {

				Route::resource('books', 'BookController');
				Route::resource('authors', 'AuthorController');
                Route::resource('category', 'CategoryController');
                Route::resource('technical_information', 'TechnicalInformationController');
                                Route::post('/files', 'FileController@upload');
                                Route::get('/files', 'FileController@index');
                                Route::delete('/files/{id}', 'FileController@destroy');
});