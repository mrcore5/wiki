<?php namespace Mrcore\Modules\Wiki\Http\Controllers\Admin;

use View;
use Config;
use Request;
use Input;
use Response;
use stdClass;
use Mrcore\Modules\Wiki\Models\Tag;
use Mrcore\Modules\Wiki\Http\Controllers\Controller;

class TagController extends Controller {

	/**
	 * Displays a DataTable
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = new stdClass();
		$data->name = 'Tag';
		$data->partial = '_tags';
		$data->dataUrl = 'tag/data';
		
		return View::make('admin.content', compact('data'));
	}

	/**
	 * Retrieves Data for the DataTable
	 * @return json
	 */
	public function getData()
	{
		$items = Tag::select(array('id as ID', 'name as Name', 'image as Image'))->get();
		foreach($items as $item) {
			$item->Image =  '<img src="/assets/uploads/'.$item->Image.'" height="24px" />';
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

		$tag = new Tag;
		$tag->name = $name;
		$tag->save();

		if (Input::hasFile('image')) {
			Input::file('image')->move(base_path()."/public/uploads/", "tag".$tag->id.".png");
			$tag->image = 'tag'.$tag->id.'.png';		
			$tag->save();	
		}

		return Response::json([
			'message' => 'Success! Tag Added!'
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
			$tag = Tag::find($id);
			$tag->name = $name;
			$tag->image = $image;

			if (Input::hasFile('image')) {
				Input::file('image')->move(base_path()."/public/uploads/", "tag".$tag->id.".png");
				$tag->image = 'tag'.$tag->id.'.png';
			}

			$tag->save();	

			return Response::json([
				'message' => 'Success! Tag Updated!'
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
			$tag = Tag::find($id);
			$tag->delete();

			return Response::json([
				'message' => 'Success! Tag Deleted!'
			], 200);
		} else {
			return Response::json([
				'message' => 'Error! Delete Failed!'
			], 400);
		}
	}

}

