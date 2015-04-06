<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Contact;
use sms\Group;

class MobileController extends Controller {

	public function getContact()
	{
		if(\Input::has('id'))
		{
			$db = Contact::where('ID',\Input::get('id'))->get();
			if($db)
			{
				return \Response::json(['success'=>1, 'data'=>$db]);
			}
			else
			{
				return \Response::json(['message'=>'Data tidak ditemukan','success'=>0]);
			}
			
		}
		else
		{
			$db = Contact::all();	
			if($db)
			{
				return \Response::json(['success'=>1, 'data'=>$db]);
			}
			else
			{
				return \Response::json(['message'=>'Data tidak ada','success'=>0]);
			}
		}
	}

	public function postContact()
	{
		if(\Input::get('action')=='delete')
		{
			$db = Contact::destroy(\Input::get('ID'));
			if($db)
			{
				return \Response::json(['success'=>1, 'message'=>'Hapus data berhasil!']);
			}
			else
			{
				return \Response::json(['success'=>0, 'message'=>'Hapus data gagal!']);
			}
		}
		else
		{
			if(\Input::has('ID')) //edit
			{
				$status = 'Edit';
				$db = Contact::find(\Input::get('ID'));
			}
			else
			{
				$status = 'Tambah';
				$db = new Contact;
			}

			$db->Name = \Input::get('Name');
			$db->Number = \Input::get('Number');
			$db->GroupID = \Input::get('GroupID');
			// if(\Input::has('GroupID')){
			// 	$dbgroup = Group::firstOrCreate(['Name'=>\Input::get('GroupID')]);
			// 	$db->GroupID = $dbgroup->ID;
			// }
			$db->save();
			if($db->ID)
			{
				return \Response::json(['success'=>1, 'message'=>$status.' data berhasil!']);
			}
			else
			{
				return \Response::json(['success'=>0, 'message'=>$status.' data gagal!']);
			}
		}
	}

	public function missingMethod($parameters = array())
	{
	    return 'Notfound';
	}

}
