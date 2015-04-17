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
		if(\Request::ajax()) 
		{
			$term = \Input::get('term');
			$filter = \Input::get('filter');
			$db = Contact::select('*');
			if($term) $db->where('Name','like','%'.$term.'%')->orWhere('Number','like','%'.$term.'%');
			if($filter) $db->where('GroupID',$filter);
			$dbr = $db->orderBy('Name','asc')->paginate();
			return \Response::json($dbr);
		}
		else
		{
			$data['list_group'] = Group::get();
			return view('contact.index')->with('data',$data);
		}
	}

	public static function newContact($number, $name, $gid='')
	{
		// Cek di database contact
		$a = Contact::where('Number',$number)->first();
		if($a)
		{
			return $a['ID'];
		}
		else
		{
			$db = new Contact;
			$db->Name = $name;
			$db->Number = $number;
			$db->GroupID = $gid;
			$db->save();
			return $db->ID;
		}
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
			/* Destination Autocomplete Response (Compose message Form)*/
			if($id==0) 
			{
				$term = \Input::get('term');
				$db = Contact::ListWithGroup($term);
				$contact = [];
				foreach ($db as $key) {
					$contact[$key->ID] = ['label'=>$key->Name, 'id'=>$key->ID, 'num'=>$key->label];
				}
				return \Response::json($contact);
			}

			/* Detail contact response */
			else 
			{
				$data = Contact::select('pbk.ID','pbk.Name','pbk.Number','pbk_groups.Name as GroupName')
									->leftJoin('pbk_groups','pbk.GroupID','=','pbk_groups.ID')->where('pbk.ID',$id)->get();
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
