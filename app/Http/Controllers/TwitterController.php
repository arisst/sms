<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;
// use sms\Twitter;

class TwitterController extends Controller {

	public function getIndex()
	{
		// return \Twitter::getUserTimeline(['screen_name' => 'arisst', 'count' => 20, 'format' => 'array']);
	    // return Twitter::getHomeTimeline(['count' => 20, 'format' => 'json']);
	    // return Twitter::getMentionsTimeline(['count' => 20, 'format' => 'json']);
	    // return Twitter::postTweet(['status' => 'Test', 'format' => 'json']);
		if(\Session::has('access_token')){
		    return view('twitter.index');
		}
		else{
			return redirect('twitter/connect');
		}
	}

	public function getToken()
	{
		$token = \Session::get('access_token');
		// return $token['oauth_token'];
		// return $token['oauth_token_secret'];
		// return $token['user_id'];
		return $token['screen_name'];
	}

	public function getTimeline()
	{
		$screen_name = \Session::get('access_token')['screen_name'];
	    // return \Twitter::getHomeTimeline(['count' => 20, 'format' => 'json']);
		return \Twitter::getUserTimeline(['screen_name' => $screen_name, 'count' => 20, 'format' => 'json']);
	}

}
