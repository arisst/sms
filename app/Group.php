<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

	protected $table = 'pbk_groups';
	protected  $primaryKey = 'ID';
	protected $fillable = ['Name'];
	public $timestamps = false;

}
