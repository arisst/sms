<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Inbox;
use sms\Outbox;
use sms\Contact;

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
		return view('inbox.form');
	}


	public function store()
	{
		$dst = \Input::get('destination');
		$msg = \Input::get('message');
		if(\Input::get('state')==0){
			$e = array_map('trim',explode(',', $dst));
			$contact = Contact::whereIn('Name',$e)->get();
			foreach ($contact as $key) {
				Outbox::create(['DestinationNumber'=>$key->Number, 'TextDecoded'=>$msg]);
			}
		}
		else
		{
			Outbox::create(['DestinationNumber'=>$dst, 'TextDecoded'=>$msg]);
		}

	}

	public function show($phone)
	{
		if(\Request::ajax()) 
		{
			// $data = Inbox::where('SenderNumber','like','%'.$phone)->get();
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
			if(\Session::get('group')=='On'){
				$db = \DB::table('inbox')->whereIn('SenderNumber', $ids)->delete();
				$db = \DB::table('sentitems')->whereIn('DestinationNumber', $ids)->delete();
			}else
			{
				$db = \DB::table('inbox')->whereIn('ID', $ids)->delete();
			}

			return $db;	
		}
	}
}
