<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

	protected $table = 'pbk';
	protected  $primaryKey = 'ID';
	public $timestamps = false;

	// public static function detail($id)
	// {
	// 	\DB::table($table)->
	// }
}
