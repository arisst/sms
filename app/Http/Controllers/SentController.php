<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Sent;

class SentController extends Controller {

	function __construct() {
		$this->middleware('auth');
		if(!\Session::has('group')) \Session::put('group','On');
	}

	public function index()
	{
		$db = Sent::listing(20);
		return view('sent.index')->with('data',$db);
	}

	public function show($phone)
	{
		if(\Request::ajax()) 
		{
			$data = Inbox::where('SenderNumber','like','%'.$phone)->get();
			return \Response::json(['inbox'=>$data]);
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

	public function destroy($id)
	{
		if(\Request::ajax()) 
		{
			$ids = explode(',', $id);
			$db = \DB::table('inbox')->whereIn('ID', $ids)->delete();
			return $db;	
		}
	}


}
