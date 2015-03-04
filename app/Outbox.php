<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Outbox extends Model {

	protected $table = 'outbox';
	protected  $primaryKey = 'ID';
	protected $fillable = ['DestinationNumber','TextDecoded'];
	public $timestamps = false;

}
