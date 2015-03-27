<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Api;
use sms\Outbox;

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
}
