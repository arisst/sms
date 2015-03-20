<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', 'WelcomeController@index');

Route::get('/', 'HomeController@index');
Route::resource('inbox', 'InboxController');
Route::resource('outbox', 'OutboxController');
Route::resource('contact', 'ContactController');
Route::resource('group', 'GroupController');
Route::resource('keyword', 'KeywordController');
Route::resource('sent', 'SentController');

Route::get('daemon', 'KeywordController@daemon');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
