<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model {

	public static function getInboxByKeyword($keyword_utama)
	{
		

		// return $keyword_utama;
		// \DB::raw('substring(isi, 3,2) as id')
		return \DB::table('view_conversation')
					// ->select('hp','isi','waktu')
					->where('tabel','=','inbox')
					->where('status','=','false')
					->where('isi','like',$keyword_utama.'%')
					->get();
		// dd($db);
	}

}
