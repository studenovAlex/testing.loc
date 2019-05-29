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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions', 'QuestionsController');

// Route::resource('answers', 'AnswersController');

Route::resource('tests', 'TestsController');

Route::get('/tests/{id}/pass', 'TestsController@pass');

Route::post('/tests/{id}/result', 'TestsController@result')->name('tests.result');

Route::get('/tests/results', 'TestsController@show');