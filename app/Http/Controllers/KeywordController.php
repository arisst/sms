<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Keyword;

class KeywordController extends Controller {

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
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(\Request::ajax()) 
		{	
			$data = Keyword::where('id','=',$id)->get();
			if($data){
				return \Response::json($data);
			}else{
				return \Response::json(null, 404);
			}
		
		}
		else
		{
			abort(404);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
