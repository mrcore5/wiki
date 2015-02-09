<?php namespace Mrcore\Modules\Wiki\Http\Controllers;

use View;
use Config;
use Request;
use Response;
use Mrcore\Modules\Wiki\Models\Post;

class AdminController extends Controller {

	/**
	 * Displays the search page
	 *
	 * @return Response
	 */
	public function showBadges()
	{
		return View::make('admin.badges');
	}


	/**
	 * Get the search dropdown menu
	 * Handles via ajax only
	 */
	public function searchMenu()
	{
		// Ajax only controller
		if (!Request::ajax()) return Response::notFound();

		$post = Post::find(Config::get('mrcore.searchmenu'));
		if (!isset($post)) return Response::notFound();

		// Parse Post Now!
		$post->parse();

		return $post->content;
	}

	public function userMenu()
	{
		return "dd";
	}

}

