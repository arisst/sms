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
		$data = Keyword::where('status','1')->get();
		foreach ($data as $row) 
		{
			$keyword = $row['keyword'];
			$url = $row['url'];

			/* Pakai keyword */
			if($keyword!='')
			{
				$second_keyword_count = preg_match_all("/\[([^\]]*)\]/", $keyword, $second_keyword_match); //[]
				#dd($second_keyword_count);
				$main_keyword = Keyword::main($keyword);
				$db = Keyword::inbox($main_keyword);
				foreach ($db as $key) 
				{
					#Inbox::process($key->id);
					unset($query);
					unset($patterns);
					unset($replacements);
					$url_match = Keyword::url($url);
					$query = ['sender' => $key->hp, 'message' => $key->isi, 'time' => $key->waktu];
					foreach ($url_match as $key1) 
					{
						$patterns[] = '/\${'.$key1.'}/';
						$replacements[] = $query[$key1];
					}
					$newurl = preg_replace($patterns, $replacements, $url).'<br>'; //${}

					if($second_keyword_count)
					{
						unset($patterns2);
						unset($replacements2);
						$main_keyword_count = strlen($main_keyword);
						$first = strtok($key->isi, " ");
						
						$explode_keyword = explode(']', substr($keyword, strlen($main_keyword)));
						$delimiter = substr($explode_keyword[1], 0, strpos($explode_keyword[1], '['));
						#dd($delimiter);

						foreach ($second_keyword_match[1] as $key2) 
						{
							$patterns2[] = '/\$\['.$key2.'\]/';
							$replacements2[] = $key->isi;#substr($key->isi, $main_keyword_count);
						}
						$newest_url = preg_replace($patterns2, $replacements2, $newurl).' (baru)<br>keyword:'.$keyword.'||isi:'.$key->isi.'<br>';
						echo $newest_url;

					}
					else
					{
						echo $newurl;
					}

				}
			}

			/* Tanpa Keyword */
			else 
			{
				$db = Inbox::where('Processed','false')->get();
				dd($db);
			} 
		}
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
