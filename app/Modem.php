<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Modem extends Model {

	protected $table = 'phones';
	protected  $primaryKey = 'IMEI';
	protected $fillable = [];
	public $timestamps = false;

	public static function gammuVersion()
	{
		$db = \DB::table('gammu')->first();
		return $db->Version;
	}

}
