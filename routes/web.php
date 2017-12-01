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

// Laravel Authentication routes
Auth::routes();

// Index Route - homepage
Route::get('/', 'SiteController@index')->name('index');

// Dashboard Route - logged in view
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

// Questions Route - question view and their multiple choice answers
Route::get('/question/{question}', 'QuestionController@show')->name('question_index');

// Questions Result Route - results view of all answers for that question
Route::get('/question/{question}/results', 'QuestionController@results')->name('question_result');

// Questions Route - processing for user answer submission
Route::post('/question/{question}/submit', 'QuestionController@store')->name('question_submit');

