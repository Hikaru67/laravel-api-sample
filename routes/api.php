<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:api']], function () {
    // Resources
    Route::apiResource('role', 'RoleController');
    Route::apiResource('user', 'UserController');
    Route::apiResource('menu', 'MenuController');
    Route::apiResource('theses', 'ThesisController');
    Route::apiResource('students', 'StudentController');
    Route::apiResource('lecturers', 'LecturerController');

    // Authenticated
    Route::post('logout', 'UserController@logout');
    Route::get('me', 'UserController@getProfile');
    Route::post('me', 'UserController@postProfile');

    // Others
    Route::post('menu/move', 'MenuController@move');
    Route::get('permission', 'RoleController@getPermissions');
});

Route::group(['middleware' => ['guest:api']], function () {
    // Guest
    Route::post('login', 'UserController@login')->name('login');
    Route::post('refresh', 'UserController@refresh');
});
