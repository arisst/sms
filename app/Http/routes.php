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
Route::resource('ews', 'EwsController');
Route::resource('modem', 'ModemController');
Route::resource('user', 'UserController');

Route::get('ewsapp', 'PublicController@ews');
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

/*
* Twitter Route
*/
Route::controller('twitter', 'TwitterController');


Route::get('atwitter/login', ['as' => 'twitter.login', function(){
    // your SIGN IN WITH TWITTER  button should point to this route
    $sign_in_twitter = true;
    $force_login = false;

    // Make sure we make this request w/o tokens, overwrite the default values in case of login.
    Twitter::reconfig(['token' => '', 'secret' => '']);
    $token = Twitter::getRequestToken(route('twitter.callback'));

    if (isset($token['oauth_token_secret']))
    {
        $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

        Session::put('oauth_state', 'start');
        Session::put('oauth_request_token', $token['oauth_token']);
        Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

        return Redirect::to($url);
    }

    return Redirect::route('twitter.error');
}]);

Route::get('atwitter/callback', ['as' => 'twitter.callback', function() {
    // You should set this route on your Twitter Application settings as the callback
    // https://apps.twitter.com/app/YOUR-APP-ID/settings
    if (Session::has('oauth_request_token'))
    {
        $request_token = [
            'token'  => Session::get('oauth_request_token'),
            'secret' => Session::get('oauth_request_token_secret'),
        ];

        Twitter::reconfig($request_token);

        $oauth_verifier = false;

        if (Input::has('oauth_verifier'))
        {
            $oauth_verifier = Input::get('oauth_verifier');
        }

        // getAccessToken() will reset the token for you
        $token = Twitter::getAccessToken($oauth_verifier);

        if (!isset($token['oauth_token_secret']))
        {
            return Redirect::route('twitter.login')->with('flash_error', 'We could not log you in on Twitter.');
        }

        $credentials = Twitter::getCredentials();

        if (is_object($credentials) && !isset($credentials->error))
        {
            // $credentials contains the Twitter user object with all the info about the user.
            // Add here your own user logic, store profiles, create new users on your tables...you name it!
            // Typically you'll want to store at least, user id, name and access tokens
            // if you want to be able to call the API on behalf of your users.

            // This is also the moment to log in your users if you're using Laravel's Auth class
            // Auth::login($user) should do the trick.

            Session::put('access_token', $token);

            return Redirect::to('/')->with('flash_notice', 'Congrats! You\'ve successfully signed in!');
        }

        return Redirect::route('twitter.error')->with('flash_error', 'Crab! Something went wrong while signing you up!');
    }
}]);

Route::get('atwitter/error', ['as' => 'twitter.error', function(){
    // Something went wrong, add your own error handling here
}]);

Route::get('atwitter/logout', ['as' => 'twitter.logout', function(){
    Session::forget('access_token');
    return Redirect::to('/')->with('flash_notice', 'You\'ve successfully logged out!');
}]);

// end Twitter route



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




