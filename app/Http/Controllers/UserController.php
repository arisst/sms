<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\User;

class UserController extends Controller {

	function __construct() {
		$this->middleware('auth');
		if(\Auth::user()->group!=1) abort(403);
	}

	public function index()
	{
		if(\Request::ajax()) 
		{
			$term = \Input::get('term');
			$filter = \Input::get('filter');
			switch ($filter) 
			{
				case 'Administrator':
					$group = 1;
					break;
				case 'User':
					$group = 2;
					break;
				case 'Unconfirmed':
					$group = 3;
					break;
				default:
					$group = null;
					break;
			}
			$db = User::where('name','like','%'.$term.'%');
			if($group) $db->where('group',$group);
			$db->orderBy('name','asc');
			$dbr = $db->paginate();
			return \Response::json($dbr);
		}
		else
		{
			return view('user.index');
		}
	}

	public function store()
	{
		$db = new User;
		$db->name = \Input::get('name');
		$db->email = \Input::get('email');
		$db->username = \Input::get('username');
		$db->password = \Hash::make(\Input::get('password'));
		$db->group = \Input::get('group');
		$db->api_key = \Input::get('api_key');
		
		$db->save();

		return \Response::json(['id'=>$db->id]);
	}

	public function show($id)
	{
		if(\Request::ajax()) 
		{	
			$data = User::find($id);
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

	public function update($id)
	{
		$db = User::find($id);
		$db->name = \Input::get('name');
		$db->email = \Input::get('email');
		$db->username = \Input::get('username');
		if(\Input::has('password')) $db->password = \Hash::make(\Input::get('password'));
		$db->group = \Input::get('group');
		$db->api_key = \Input::get('api_key');
		$db->save();
		return \Response::json(['id'=>$db->id]);
	}

	public function destroy($id)
	{
		if(\Request::ajax()) 
		{
			$ids = explode(',', $id);
			$db = \DB::table('users')->whereIn('id', $ids)->delete();
			return $db;	
		}
	}

}
