<?php namespace sms;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model {

	public static function inbox($main_keyword)
	{
		$db = \DB::table('view_conversation');
		$db->where('tabel','=','inbox');
		$db->where('status','=','false');
		$db->where('isi','like',$main_keyword.'%');
		return $db->get();
	}

	public static function main($keyword)
	{
		$keyword_bracket_position = strpos($keyword, '[');
		$main_keyword = ($keyword_bracket_position) ? substr($keyword, 0, $keyword_bracket_position) : $keyword ;
		return $main_keyword;
	}

	public static function url($url)
	{
		$regex_url_count = preg_match_all('/\${(.*?)}/', $url, $regex_url_match); //${}
		return $regex_url_match[1];
	}

}
