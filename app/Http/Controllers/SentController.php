<?php namespace sms\Http\Controllers;

use sms\Http\Requests;
use sms\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sms\Sent;

class SentController extends Controller {

	function __construct() {
		$this->middleware('auth');
		if(!\Session::has('group')) \Session::put('group','On');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$db = Sent::listing(20);
		return view('sent.index')->with('data',$db);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
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
	public function show($phone)
	{
		if(\Request::ajax()) 
		{
			$data = Inbox::where('SenderNumber','like','%'.$phone)->get();
			return \Response::json(['inbox'=>$data]);
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
		if ($id) {
			\Session::put('group','On');
		}
		else
		{
			\Session::put('group', 'Off');
		}
		return redirect()->back();
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
		if(\Request::ajax()) 
		{
			$ids = explode(',', $id);
			$db = \DB::table('inbox')->whereIn('ID', $ids)->delete();
			return $db;	
		}
	}


}
