<?php namespace Mrcore\Wiki\Http\Controllers;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		dd('x');
		/*$userTable = $this->getUserDatatable();
		if (Request::ajax()) {
			Render::connection('vfi')->datatables('clientTable', $clientTable);
		} else {
			$post = Mrcore::post()->prepare();
			return View::make('vfi::client.index', compact(
				'post', 'clientTable'
			));
		}


		return View::make('user.index', array(
		));*/
		#dd(Config::get('database.connections.mysql_dbal'));

		#$sql = \App::make("Mreschke\Dbal\Mssql");
		#$x = \Mysql::connection(Config::get('database.connections.mysql_dbal'))->query("SELECT * FROM users");
		#dd($x->get());

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
	public function show($id)
	{
		//
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
