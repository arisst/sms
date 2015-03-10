<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Inbox;
use sms\Outbox;
use sms\Contact;
use sms\Group;

class InboxController extends Controller {

	function __construct() {
		$this->middleware('auth');
		if(!\Session::has('group')) \Session::put('group','On');
	}

	public function index()
	{
		if (\Session::get('group')=='On') {
			$db = Inbox::grouping();
			$view = 'inbox.index';
		}
		else{
			$db = Inbox::listing(20);
			$view = 'inbox.inbox';
		}
		return view($view)->with('data',$db);
	}


	public function create()
	{
		$id = '085259,081234';
		$ids = explode(',', $id);
		$ida = array_map(function($str){return str_replace('0', '+62', $str);}, $ids);
		
		return $ida;
	}


	public function store()
	{
		$dst = \Input::get('destination');
		$msg = \Input::get('message');
		if(\Input::get('state')==0)
		{
			$e = array_map('trim',explode(',', $dst));
			foreach ($e as $key) 
			{
				if($key)
				{
					$contact = Contact::where('Name','=',$key)->first();
					$group = Group::where('Name','=',$key)->first();
					if($contact)
					{
						return Outbox::create(['DestinationNumber'=>$contact['Number'], 'TextDecoded'=>$msg]);
					}
					else
					{
						if($group)
						{
							return Outbox::SendToGroup($group->Name, $msg);
						}
						else
						{
							return Outbox::create(['DestinationNumber'=>$key, 'TextDecoded'=>$msg]);
						}
					}
				}
			}
		}
		else
		{
			return Outbox::create(['DestinationNumber'=>$dst, 'TextDecoded'=>$msg]);
		}

	}

	public function show($phone)
	{
		if(\Request::ajax()) 
		{
			$data = Inbox::conversation($phone);
			if($data){
				return \Response::json($data);
			}else{
				return \Response::json(null, 404);
			}
		}
	}

	public function edit($id)
	{
		if ($id) {
			\Session::put('group','On');
		}
		else
		{
			\Session::put('group', 'Off');
		}
		return redirect()->back();
	}

	public function update($id)
	{
		//
	}

	public function destroy($id)
	{
		if(\Request::ajax()) 
		{
			$ids = explode(',', $id);
			$ida = array_map(function($str){return str_replace('0', '+62', $str);}, $ids);
			if(\Session::get('group')=='On'){
				$db = \DB::table('inbox')->whereIn('SenderNumber', $ids)->delete();
				$db = \DB::table('sentitems')->whereIn('DestinationNumber', $ids)->delete();
				$db = \DB::table('sentitems')->whereIn('DestinationNumber', $ida)->delete();
			}else
			{
				$db = \DB::table('inbox')->whereIn('ID', $ids)->delete();
			}

			return $db;	
		}
	}
}
