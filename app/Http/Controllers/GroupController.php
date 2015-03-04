<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Group;

class GroupController extends Controller {

	public function index()
	{
		$db = Group::all();
		return \Response::json($db);
	}

	public function create()
	{
		//
	}

	public function store()
	{
		//
	}

	public function show($id)
	{
		if(\Request::ajax()) 
		{
			$term = \Input::get('term');
			$db = Group::where('Name','like',$term.'%')->get();
			// dd($db);
			$group = [];
			foreach ($db as $key) {
				$group[$key->ID] = ['label'=>$key->Name, 'id'=>$key->ID];
			}
			return \Response::json($group);
		}
	}

	public function edit($id)
	{
		//
	}

	public function update($id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}

}
