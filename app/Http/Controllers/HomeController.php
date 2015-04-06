<?php namespace sms\Http\Controllers;

use sms\Inbox;
use sms\Sent;
class HomeController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
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
			$signal = '53';
			return view('home')->with('data', ['signal'=>$signal, 'category'=>$category]);
		}
	}
}
