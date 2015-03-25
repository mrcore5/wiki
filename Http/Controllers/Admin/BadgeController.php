<?php namespace Mrcore\Modules\Wiki\Http\Controllers\Admin;

use View;
use Config;
use Request;
use Input;
use stdClass;
use Response;
use Mrcore\Modules\Wiki\Models\Badge;
use Mrcore\Modules\Wiki\Http\Controllers\Controller;

class BadgeController extends Controller {

	/**
	 * Displays a DataTable
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = new stdClass();
		$data->name = 'Badge';
		$data->partial = '_badges';
		$data->dataUrl = 'badge/data';
		
		return View::make('admin.content', compact('data'));
	}

	/**
	 * Retrieves Data for the DataTable
	 * @return json
	 */
	public function getData()
	{
		$items = Badge::select(array('id as ID', 'name as Name', 'image as Image'))->get();
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
		$image = Input::get('image');

		$badge = new Badge;
		$badge->name = $name;
		$badge->image = $image;
		$badge->save();

		return Response::json([
			'message' => 'Success! Badge Added!'
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
		$image = Input::get('image');

		if ($id != 0) {
			$badge = Badge::find($id);
			$badge->name = $name;
			$badge->image = $image;
			$badge->save();

			return Response::json([
				'message' => 'Success! Badge Updated!'
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
			$badge = Badge::find($id);
			$badge->delete();

			return Response::json([
				'message' => 'Success! Badge Deleted!'
			], 200);
		} else {
			return Response::json([
				'message' => 'Error! Delete Failed!'
			], 400);
		}
	}

}

