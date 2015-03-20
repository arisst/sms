<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Keyword;
use sms\Inbox;

class KeywordController extends Controller {

	function __construct() 
	{
		$this->middleware('auth');
	}

	public function daemon()
	{
			$id = '3';
			$data = Keyword::find($id);
			$keyword = $data['keyword'];
			$posisi = strpos($keyword, '[');
			$keyword_utama = ($posisi) ? substr($keyword, 0,$posisi) : $keyword ;
			$count1 = preg_match_all("/\[([^\]]*)\]/", $keyword, $matches1); //[]
			$count2 = preg_match_all('/\${(.*?)}/', $data['url'], $matches2); //${}
			$db = Keyword::getInboxByKeyword($keyword_utama);
			foreach ($db as $key) {
				$query = ['hp' => $key->hp, 'isi' => $key->isi, 'waktu' => $key->waktu];
			}
			foreach ($matches2[1] as $key) {
				$patterns[] = '/\${'.$key.'}/';
				$replacements[] = $query[$key];
			}
			$newtext = preg_replace($patterns, $replacements, $data['url']); //${}
			$a = $matches2[1][0].'<br>'.$data['url'].'<br>'.$newtext;

			return $a;
	}

	public function index()
	{
		if(\Request::ajax()) 
		{
			$term = \Input::get('term');
			$db = Keyword::where('name','like','%'.$term.'%')->orWhere('keyword','like','%'.$term.'%')->orderBy('name','asc')->paginate();
			return \Response::json($db);
		}
		else
		{
			return view('keyword.index');
		}
	}

	public function create()
	{
		\Queue::push('Keyword');
	}

	public function store()
	{
		$db = new Keyword;
		$db->name = \Input::get('name');
		$db->keyword = \Input::get('keyword');
		$db->url = \Input::get('url');
		$db->method = \Input::get('method');
		$db->params = \Input::get('params');
		$db->save();
		return \Response::json(['id'=>$db->id]);
	}

	public function show($id)
	{
		if(\Request::ajax()) 
		{	
			$data = Keyword::where('id','=',$id)->get();
			if($data)
			{
				return \Response::json($data);
			}
			else
			{
				return \Response::json(null, 404);
			}
		
		}
		else
		{

		}
	}

	public function edit($id)
	{
		//
	}

	public function update($id)
	{
		$db = Keyword::find($id);
		$db->name = \Input::get('name');
		$db->keyword = \Input::get('keyword');
		$db->url = \Input::get('url');
		$db->method = \Input::get('method');
		$db->params = \Input::get('params');
		$db->save();
		return \Response::json(['id'=>$db->id]);
	}

	public function destroy($id)
	{
		if(\Request::ajax()) 
		{
			$ids = explode(',', $id);
			$db = \DB::table('keywords')->whereIn('id', $ids)->delete();
			return $db;	
		}
	}

}
