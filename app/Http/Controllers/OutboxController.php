<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Outbox;

class OutboxController extends Controller {

	public function show($id)
	{
		$db = Outbox::destroy($id);
			return $db;	
	}

	public function destroy($id)
	{
		if(\Request::ajax()) 
		{
			$db = Outbox::destroy($id);
			return $db;	
		}
	}

}
