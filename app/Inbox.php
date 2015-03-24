<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model {

	protected $table = 'inbox';

	public static function grouping()
	{
		$term = \Input::get('term');
		/*return \DB::table('view_conversation')
					->select('view_conversation.*',\DB::raw('max(waktu) as wkt'),'pbk.Name')
					->leftJoin('pbk','pbk.Number','=','view_conversation.hp')
					->groupBy('hp')
					->orderBy('wkt','desc')
					->get();*/

		// return \DB::select(\DB::raw("SELECT sub.*,pbk.Name FROM (SELECT * from view_conversation ORDER BY waktu desc) AS sub left join pbk on(pbk.Number=sub.hp) GROUP BY sub.hp ORDER BY waktu desc"));
		return \DB::table(\DB::raw('(SELECT * from view_conversation ORDER BY waktu desc) AS sub'))
						->select(\DB::raw('sub.*,pbk.Name'))
						->leftJoin('pbk','pbk.Number','=','sub.hp')
						->where('sub.hp','like','%'.$term.'%')
						->orWhere('pbk.Name','like','%'.$term.'%')
						->groupBy('sub.hp')
						->orderBy('waktu','desc')
						// ->paginate();
						->get();
	}
	
	public static function listing($perpage = '')
	{
		if(\Session::get('group')=='Off')
		{
			$db = \DB::table('inbox');
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

	public static function conversation($hp)
	{
		return \DB::table('view_conversation')
					->select('view_conversation.*','pbk.Name')
					->leftJoin('pbk','pbk.Number','=','view_conversation.hp')
					->where('hp', '=', $hp)
					->orderBy('waktu','asc')
					->get();
	}

	public static function process($id)
	{
		return \DB::table('inbox')
					->where('ID',$id)
					->update(['Processed'=>'true']);
	}

}
