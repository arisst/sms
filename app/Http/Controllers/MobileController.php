<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Contact;
use sms\Group;
use sms\User;

class MobileController extends Controller {

	public function postLogin()
	{
		if(\Input::get('tag')=='login')
		{
			$email = \Input::get('email');
			$password = \Input::get('password');

			if(\Auth::attempt(['email' => $email, 'password'=>$password]))
			{
				if (\Auth::user()->group < 3) {
					$response["error"] = FALSE;
					$response["uid"] = \Auth::id();
					$response["user"]["name"] = \Auth::user()->name;
					$response["user"]["email"] = \Auth::user()->email;
					$response["user"]["username"] = \Auth::user()->username;
					$response["user"]["created_at"] = \Auth::user()->created_at;
					$response["user"]["updated_at"] = \Auth::user()->updated_at;

					return \Response::json($response);
				}
				else
				{
					$response["error"] = TRUE;
					$response["error_msg"] = "Akun anda belum aktif!";
				}
			}
			else
			{
				$response["error"] = TRUE;
				$response["error_msg"] = "Username atau password salah!";
			}
		}
		else
		{
			$response["error"] = TRUE;
			$response["error_msg"] = "TAG required!";
		}
		return \Response::json($response);
	}

	public function postRegister()
	{
		if(\Input::get('tag')=='register')
		{
			$validator = \Validator::make(\Input::all(), 
				[
					'name'=>'required',
					'email'=>'required|email|unique:users', 
					'username'=>'required|unique:users',
					'password'=>'required'
				]);
			if(!$validator->fails())
			{
				$user = new User;
				$user->name = \Input::get('name');
				$user->email = \Input::get('email');
				$user->username = \Input::get('username');
				$user->password = \Hash::make(\Input::get('password'));

				if($user->save())
				{
					$response["error"] = FALSE;
					$response["uid"] = $user->id;
					$response["user"]["name"] = $user->name;
					$response["user"]["email"] = $user->email;
					$response["user"]["username"] = $user->username;
					$response["user"]["created_at"] = $user->created_at;
					$response["user"]["updated_at"] = $user->updated_at;
				}
				else
				{
					$response["error"] = TRUE;
					$response["error_msg"] = "Data gagal disimpan!";
				}
			}
			else
			{
				$messages = $validator->messages();
				$response["error"] = TRUE;
				$response["error_msg"] = $messages->toJson();
			}

		}
		else
		{
			$response["error"] = TRUE;
			$response["error_msg"] = "TAG required!";
		}
		return \Response::json($response);
	}

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
	    return \Response::json('Notfound', 404);
	}

}
