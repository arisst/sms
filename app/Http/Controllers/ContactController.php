<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Contact;
use sms\Group;
class ContactController extends Controller {

	function __construct() {
		$this->middleware('auth');
	}

	public function index()
	{
		$db = Contact::all();
		return view('contact.index')->with('data',$db);
	}

	public function create()
	{
		//
	}

	public function store()
	{
		$db = new Contact;
		$db->Name = \Input::get('name');
		$db->Number = \Input::get('number');
		if(\Input::has('group')){
			$dbgroup = Group::firstOrCreate(['Name'=>\Input::get('group')]);
			$db->GroupID = $dbgroup->ID;
		}
		$db->save();
		return \Response::json(['id'=>$db->ID]);
	}

	public function show($id)
	{
		if(\Request::ajax()) 
		{
			if($id==0)
			{
				$term = \Input::get('term');
				$db = Contact::where('Name','like',$term.'%')->get();
				// dd($db);
				$contact = [];
				foreach ($db as $key) {
					$contact[$key->ID] = ['label'=>$key->Name, 'id'=>$key->ID];
				}
				return \Response::json($contact);
			}
			else
			{
				$data = Contact::select('pbk.ID','pbk.Name','pbk.Number','pbk_groups.Name as GroupName')
									->leftJoin('pbk_groups','pbk.GroupID','=','pbk_groups.ID')->where('pbk.ID',$id)->get();
				// dd($data);
				if($data){
					return \Response::json($data);
				}else{
					return \Response::json(null, 404);
				}
			}
		}
		else
		{
			abort(404);
		}
	}

	public function edit($id)
	{
		//
	}

	public function update($id)
	{
		$db = Contact::find($id);
		$db->Name = \Input::get('name');
		$db->Number = \Input::get('number');
		if(\Input::has('group')){
			$dbgroup = Group::firstOrCreate(['Name'=>\Input::get('group')]);
			$db->GroupID = $dbgroup->ID;
		}else{
			$db->GroupID = '-1';
		}
		$db->save();
		return \Response::json(['id'=>$db->ID]);
	}

	public function destroy($id)
	{
		if(\Request::ajax()) 
		{
			$ids = explode(',', $id);
			$db = \DB::table('pbk')->whereIn('ID', $ids)->delete();
			return $db;	
		}
	}

}
