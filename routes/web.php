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

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

// Questions Route - question view and their multiple choice answers
Route::get('/question/{question}', 'QuestionController@show')->name('question_index');

// Questions Route - processing for user answer submission
Route::post('/question/{question}/submit', 'AnswerController@store')->name('question_submit');

