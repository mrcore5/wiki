<?php namespace Mrcore\Modules\Wiki\Http\Controllers;

use View;
use Input;
use Layout;
use Mrcore;
use Request;
use Response;
use Redirect;
use Mrcore\Models\Post;
use Mrcore\Models\Revision;

class PostController extends Controller {

	/**
	 * Display a single post, defaults to home page if no $id
	 *
	 * @return Response
	 */
	public function showPost()
	{
		// Redirect router results here (not in the middleware)
		$router = Mrcore::router();
		if (isset($router)) {
			if ($router->responseCode == 404) {
				return Response::notFound();
			} elseif ($router->responseCode == 401) {
				return Response::denied();
			} elseif ($router->responseCode == 301) {
				// Redirect to proper url
				$url = $router->responseRedirect;
				return Redirect::to($url);
			}
		}
	
		// Gets post, parse + globals
		// If ajax, do NOT include globals
		$post = Mrcore::post()->prepare(!Request::ajax());

		// If post is a workbench and we get to this point then
		// The custom workbench route was not found, meaning we
		// want to return 404 for this url
		#if ($post->workbench) {
		#	return Response::notFound();
		#}

		# Set bootstrap container based on post type
		if ($post->type->constant == 'app') {
			// Apps have no container (full screen), all others are system default
			Layout::container(false);
		}

		// Show Post View
		$content = View::make('post.show', compact(
			'post', 'container'
		));

		if (Layout::modeIs('raw') && strtolower($post->format->constant) == 'text') {
			// Raw mode with text format, force return to text/plain
			// or else it shows as text/html
			$response = Response::make($content, 200);
			$response->header('Content-Type', 'text/plain');
			return $response;
		} else {
			return $content;
		}

	}

	/**
	 * Shows a post revision by revision ID
	 */
	public function showRevision($id)
	{

	}

	/**
	 * Delete an uncommitted revision by postID and userID
	 */
	public function deleteRevision()
	{
		// Ajax only controller
		if (!Request::ajax()) return Response::notFound();

		$postID = Input::get('postID');
		$userID = Input::get('userID');
		if ($postID > 0 && $userID > 0 && Mrcore::user()->isAuthenticated()) {
			$post = Post::get($postID);
			if (isset($post)) {
				if ($post->hasPermission('write')) {
					$revision = Revision::where('post_id', '=', $postID)
						->where('created_by', '=', $userID)
						->where('revision', '=', 0)
						->first();
					if (isset($revision)) {
						$revision->delete();
					}
				} else {
					return Response::notFound();
				}
			} else {
				return Response::notFound();
			}
		} else {
			return Response::notFound();
		}

	}

}