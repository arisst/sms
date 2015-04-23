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

Route::controller('mobile','MobileController');

Route::get('home', 'HomeController@index');
Route::get('/', 'HomeController@index');
Route::resource('inbox', 'InboxController');
Route::resource('outbox', 'OutboxController');
Route::resource('contact', 'ContactController');
Route::resource('group', 'GroupController');
Route::resource('keyword', 'KeywordController');
Route::resource('api', 'ApiController');
Route::resource('modem', 'ModemController');
Route::resource('user', 'UserController');

Route::get('kirimsms', 'PublicController@kirimsms');
Route::resource('sent', 'SentController');

// Route::get('daemon', 'KeywordController@daemon');
Route::get('daemon', 'PublicController@eksekusi');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
Route::get('profile','HomeController@profile');
Route::post('profile','HomeController@doProfile');

Route::get('inbox/export/{hp}','InboxController@export');

// // Oauth2 server routes (route to respond to the access token requests)
// Route::post('oauth/access_token', function() {
//     return Response::json(Authorizer::issueAccessToken());
// });

// // Oauth code grant incoming request
// Route::get('oauth/authorize', ['before' => 'check-authorization-params|auth', function() {
//     // display a form where the user can authorize the client to access it's data
//     return View::make('oauth/authorization-form', Authorizer::getAuthCodeRequestParams());
// }]);

// // route to respond to the form being posted
// Route::post('oauth/authorize', ['before' => 'csrf|check-authorization-params|auth', function() {

//     $params['user_id'] = Auth::user()->id;

//     $redirectUri = '';

//     // if the user has allowed the client to access its data, redirect back to the client with an auth code
//     if (\Input::get('approve') !== null) {
//         $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
//     }

//     // if the user has denied the client to access its data, redirect back to the client with an error message
//     if (\Input::get('deny') !== null) {
//         $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
//     }

//     return Redirect::to($redirectUri);
// }]);

// // contoh halaman setelah login
// Route::get('o1', ['before' => 'oauth', function() {
//     // return the protected resource
// }]);
// Route::get('o2', ['before' => 'oauth:scope1,scope2', function() {
//     // return the protected resource
// }]);

// // oauth client
// Route::get('oauth/login', function ()
// {
// 	return view('oauth.login');
// });

// Route::post('oauth/login', 'OauthController@doLogin');




