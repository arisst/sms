<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Outbox extends Model {

	protected $table = 'outbox';
	protected  $primaryKey = 'ID';
	protected $fillable = ['DestinationNumber','SendingDateTime','TextDecoded','CreatorID'];
	public $timestamps = false;

	public static function SendToGroup($name, $text, $schedule = null)
	{
		$data = \DB::table('pbk')
					->select('pbk.Number', 'pbk_groups.ID')
					->join('pbk_groups','pbk.GroupID','=','pbk_groups.ID')
					->where('pbk_groups.Name','=',$name)
					->get();

		foreach ($data as $key) {
			$db = self::create(['DestinationNumber'=>$key->Number, 'SendingDateTime'=>$schedule, 'TextDecoded'=>$text, 'CreatorID'=>'users.'.\Auth::user()->id]);
		}
		return $db;
	}
}
