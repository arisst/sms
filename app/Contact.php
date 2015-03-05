<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

	protected $table = 'pbk';
	protected  $primaryKey = 'ID';
	public $timestamps = false;

	public static function ListWithGroup($term)
	{
		return \DB::select(
			\DB::raw("
				select ID, Name, Number as label from pbk where Name like '$term%'
				union
				select ID, Name, 'group' as label from pbk_groups where Name like '$term%'"
				)
			);
	}
}
