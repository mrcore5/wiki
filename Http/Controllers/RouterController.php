<?php namespace Mrcore\Modules\Wiki\Http\Controllers;

use Auth;
use View;
use Mrcore;
use Layout;
use Response;
use Mrcore\Modules\Wiki\Models\Router;

class RouterController extends Controller {

	/**
	 * Display a single post, defaults to home page if no $id
	 *
	 * @return Response
	 */
	public function showRouter()
	{
		if (!Auth::admin()) return Response::denied();

		$router = Router::getRoutes();

		Layout::container(false);

		return View::make('router.show', array(
			'router' => $router,
		));

	}

}