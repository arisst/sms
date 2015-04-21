<?php namespace sms\Http\Controllers;

use sms\Inbox;
use sms\Sent;
use sms\Modem;
use sms\User;
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

	public function doProfile()
	{
		$validator = \Validator::make(\Input::all(),
						[
							'name'=>'required',
							'username'=>'required|alpha_num|unique:users,username,'.\Auth::id(),
							'email'=>'required|email|unique:users,email,'.\Auth::id()
						]);
		if(!$validator->fails())
		{
			$db = User::find(\Auth::id());
			$db->name = \Input::get('name');
			$db->username = \Input::get('username');
			$db->email = \Input::get('email');
			$db->api_key = \Input::get('api_key');
			if(\Input::has('password')) $db->password = \Hash::make(\Input::get('password'));

			if($db->save()){
				return \Response::json(['msg' => 'Update profil berhasil']);
			}
			else
			{
				return \Response::json(['msg' => 'Simpan data gagal!']);
			}
		}
		else
		{
			$messages = $validator->messages();
			return \Response::json(['msg' => $messages->toJson()]);
		}
	}
}
