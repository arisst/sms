<?php namespace sms\Http\Controllers;

use sms\Inbox;
use sms\Sent;
use sms\Modem;
class HomeController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		// STATISTIK
		$dbinbox = Inbox::statistic();
		foreach ($dbinbox as $key) {
			$category[] = $key->periode;
			$inbox[] = $key->total;
		}
		$dbsent = Sent::statistic();
		foreach ($dbsent as $key) {
			$sent[] = $key->total;
		}
	
		if(\Request::ajax())
		{
			$data = [
						[
							'name'=>'Inbox',
							'data'=> $inbox
						],
						[
							'name'=>'Sent',
							'data'=> $sent
						]
					];

			return \Response::json($data,200,[],JSON_NUMERIC_CHECK);
		}
		else
		{
			$signal = Modem::select('Signal')->first();
			return view('home')->with('data', ['signal'=>$signal['Signal'], 'category'=>$category]);
		}
	}

	public function profile()
	{
		return view('user.profile');
	}
}
