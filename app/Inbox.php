<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model {

	protected $table = 'inbox';

	public static function grouping()
	{
		$term = \Input::get('term');
		$filter = \Input::get('filter');
		$db = \DB::table(\DB::raw('(SELECT * from view_conversation ORDER BY waktu desc) AS sub'))
						->select(\DB::raw('sub.id,sub.isi,sub.hp,pbk.Name'))
						->leftJoin('pbk','pbk.Number','=','sub.hp');
		if($term)
		{ 
			$db->where('sub.hp','like','%'.$term.'%');
			$db->orWhere('pbk.Name','like','%'.$term.'%');
		}
		if($filter != 'Semua' && $filter!='') $db->where('isi','like',$filter.'%');
		return	$db->groupBy('sub.hp')
						->orderBy('waktu','desc')
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
					->select('view_conversation.*','pbk.Name','users.username as author_name')
					->leftJoin('pbk','pbk.Number','=','view_conversation.hp')
					->leftJoin('users','users.id','=', \DB::raw('SUBSTRING_INDEX(view_conversation.author,".",-1)'))
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

	public static function statistic()
	{
		return \DB::table('inbox')
					->select('ReceivingDateTime',\DB::raw('count(ReceivingDateTime) as total'), \DB::raw('DATE_FORMAT(ReceivingDateTime, "%Y-%m") as periode'))
					->groupBy('periode')
					->get();
	}

}
