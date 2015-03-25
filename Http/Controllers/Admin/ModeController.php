<?php namespace Mrcore\Modules\Wiki\Http\Controllers\Admin;

use View;
use Config;
use Request;
use Input;
use Response;
use stdClass;
use Mrcore\Modules\Wiki\Models\Mode;
use Mrcore\Modules\Wiki\Http\Controllers\Controller;

class ModeController extends Controller {

	/**
	 * Displays a DataTable
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = new stdClass();
		$data->name = 'Mode';
		$data->partial = '_modes';
		$data->dataUrl = 'mode/data';
		
		return View::make('admin.content', compact('data'));
	}

	/**
	 * Retrieves Data for the DataTable
	 * @return json
	 */
	public function getData()
	{
		$items = Mode::select(array('id as ID', 'name as Name', 'constant as Constant'))->get();
		foreach($items as $item) {
			$item->Action = '<button class="btn btn-sm btn-warning btn-edit"><i class="fa fa-edit"></i></button>
							 <button class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i>
							</button>';
		}
		return $items;
	}

	/**
	 * Adds a new entry
	 * @return response
	 */
	public function store()
	{
		$name = Input::get('name');
		$constant = Input::get('constant');

		$framework = new Mode;
		$framework->name = $name;
		$framework->constant = $constant;
		$framework->save();

		return Response::json([
			'message' => 'Success! Mode Added!'
		], 200);
	}

	/**
	 * Updates an existing entry
	 * @return response
	 */
	public function update()
	{
		$id = Input::get('id');
		$name = Input::get('name');
		$constant = Input::get('constant');

		if ($id != 0) {
			$framework = Mode::find($id);
			$framework->name = $name;
			$framework->constant = $constant;
			$framework->save();

			return Response::json([
				'message' => 'Success! Mode Updated!'
			], 200);
		} else {
			return Response::json([
				'message' => 'Error! Update Failed!'
			], 400);
		}
	}

	/**
	 * Deletes an existing entry
	 * @return response
	 */
	public function destroy()
	{
		$id = Input::get('id');
		
		if ($id != 0) {
			$framework = Mode::find($id);
			$framework->delete();

			return Response::json([
				'message' => 'Success! Mode Deleted!'
			], 200);
		} else {
			return Response::json([
				'message' => 'Error! Delete Failed!'
			], 400);
		}
	}

}

