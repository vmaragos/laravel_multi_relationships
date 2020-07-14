<?php

use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); //laravel's route for all authentication routes (login, register, reset password, etc.)

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/projects', function () {
//     return view('projects');
// });

Route::get('/projects', 'ProjectController@index')->middleware('auth');

Route::get('/projects/{project}', 'ProjectController@show')->middleware('auth');;

Route::post('/projects', 'ProjectController@store');

Route::delete('/projects/{project}', 'ProjectController@destroy');

Route::post('/projects/{project}/add_user', 'ProjectController@add_user');

Route::delete('/projects/{project}/remove_user/{member}', 'ProjectController@remove_user');