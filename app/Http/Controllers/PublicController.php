<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Api;
use sms\Outbox;
use sms\Ews;

use sms\Http\Controllers\KeywordController as KeywordController;

class PublicController extends Controller {

	public function kirimsms()
	{
		$token = \Input::get('token');
		$db = Api::where('token', $token)->first();
		if($db)
		{
			if($db['access_ip']!='' && \Request::getClientIp()!=$db['access_ip']) abort(404);
			$rules = [
				'message' => 'required',
				'number' => 'required|between:10,14',
			];
			$validator = \Validator::make(\Input::all(), $rules);
			if($validator->fails()) 
			{
				$return = $validator->messages()->toJson();
				return \Response::json($return);
			}
			else
			{
				$message = \Input::get('message');
				$number = trim(\Input::get('number'));
				$send = Outbox::create(['DestinationNumber'=>$number, 'TextDecoded'=>$message, 'CreatorID'=>'apis.'.$db['id']]);
				return 1;
			}
		}
		else
		{
			abort(403);
		}
	}

	public function ews()
	{
		$app_id = \Input::get('app_id');
		$name = \Input::get('name');
		$region = \Input::get('region');
		$scale = \Input::get('scale');
		$phone = \Input::get('phone');
		$ip = \Input::get('ip');

		$app_id_in_db = Ews::where('app_id',$app_id)->first();
		if($app_id_in_db) //perform update
		{
			$ews = Ews::find($app_id_in_db['id']);
			$ews->app_id = $app_id;
			$ews->name = $name;
			$ews->region = $region;
			$ews->scale = $scale;
			$ews->phone = $phone;
			$ews->ip = \Request::getClientIp();

			if($ews->save()){
				return 'Update success';
			}
			else{
				return 'Update error';
			}
		}
		else //perform create
		{
			if($app_id && $name && $region && $scale && $phone)
			{
				$ews = new Ews;
				$ews->app_id = $app_id;
				$ews->name = $name;
				$ews->region = $region;
				$ews->scale = $scale;
				$ews->phone = $phone;
				$ews->ip = \Request::getClientIp();

				if($ews->save()){
					return 'Create success';
				}
				else{
					return 'Create error';
				}
			}
			else{
				return 'Missing parameters. Ex:'.url('ewsapp').'?app_id=EWS001&name=Aris+Setyono&region=TRENGGALEK&scale=4&phone=085259838599';
			}
		}
	}

	function curl_file_get_contents($url)
	{
		$curl = curl_init();
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		 
		curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,15); //The number of seconds to wait while trying to connect.	
		 
		curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
		curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
		curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.	
		 
		$contents = curl_exec($curl);
		curl_close($curl);
		return $contents;
	}

	public function eksekusi()
	{
		$a = KeywordController::daemon();
		$b[] = '';
		foreach ($a as $key) {
			if($key)
			{
				$this->curl_file_get_contents($key);
				$b[] = 'Pushed :'.$key;
			}
			else
			{
				$b[] = "No data to push";
			}
		}
		return \Response::json($b);
	}

	
}
