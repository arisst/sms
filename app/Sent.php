<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Sent extends Model {

	protected $table = 'sentitems';
	
	public static function listing($perpage = '')
	{
		if(\Session::get('group')=='Off')
		{
			$db = \DB::table('sentitems');
		}
		else
		{
			$db = \DB::table('inbox_groups');
		}

		if(\Input::has('filter'))
		{ 
			switch (\Input::get('filter')) {
				case 'phone':
					$db->where('SenderNumber', 'like', '%'.\Input::get('q').'%');
					break;
				case 'text':
					$db->where('TextDecoded', 'like', '%'.\Input::get('q').'%');
					break;
				default:
					$db->where('SenderNumber', 'like', '%'.\Input::get('q').'%');
					$db->orWhere('TextDecoded', 'like', '%'.\Input::get('q').'%');
					break;
			}
		}

		if (\Input::has('sort')) 
		{
			switch (\Input::get('sort')) {
				case 'phone':
					$db->orderBy('SenderNumber', 'asc');
					break;
				case 'text':
					$db->orderBy('TextDecoded', 'asc');
					break;
				case 'time':
					$db->orderBy('ReceivingDateTime', 'asc');
					break;
				default:
					$db->orderBy('ID', 'desc');
					break;
			}	
		}
		else
		{
			$db->orderBy('ID', 'desc');
		}
		if ($perpage) {
			return $db->paginate($perpage);
		}else{
			return $db->get();
		}
	}

}
