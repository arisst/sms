<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Modem;

class ModemController extends Controller {

	function __construct() {
		$this->middleware('auth');
		if(\Auth::user()->group!=1) abort(403);
	}

	public function index()
	{
		if(\Request::ajax()) 
		{
			$term = \Input::get('term');
			$db = Modem::where('IMEI','like','%'.$term.'%')->orWhere('Client','like','%'.$term.'%')->orderBy('IMEI','asc')->paginate();
			return \Response::json($db);
		}
		else
		{
			return view('modem.index')->with('data', Modem::gammuVersion());
		}
	}


	public function show($id)
	{
		if(\Request::ajax()) 
		{	
			$data = Modem::find($id);
			if($data){
				return \Response::json($data);
			}else{
				return \Response::json(null, 404);
			}
		
		}
		else
		{
			abort(404);
		}
	}
}
