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

//Routing for the API Module
Route::group(['namespace' => 'Frontend', 'prefix' => 'api/v1', 'as' => 'frontend.'], function () {
    require(__DIR__ . '/frontend/access.php');
});

//Routing for the backend Module
Route::group(['namespace' => 'Backend', 'middleware' => 'auth', 'as' => 'backend.'], function () {
    require(__DIR__ . '/backend/access.php');
});

//Authentication Routes
Auth::routes();
