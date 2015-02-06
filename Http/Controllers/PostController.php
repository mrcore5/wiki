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

		dd('post controller');

		$router = Mrcore::router();
		if (isset($router)) {
			if ($router->responseCode == 404) {
				return Response::notFound();
			} elseif ($router->responseCode == 401) {
				return Response::denied();
			} elseif ($router->responseCode == 301) {
				// Redirect to proper url
				$url = $router->responseRedirect;
				#if (is_array($router->responseRedirect)) {
					#$url = route(
					#	$router->responseRedirect['route'],
					#	$router->responseRedirect['params']
					#);
					#$url .= $router->responseRedirect['query'];
				#} else {
				#	$url = $router->responseRedirect;
				#}
				return Redirect::to($url);
			}
		}
		

		# Gets post, parse + globals
		# If ajax, do NOT include globals
		$post = Mrcore::post()->prepare(!Request::ajax());


		#echo "PostController HERE!<br />";
		#echo "Auth Check: ".\Auth::check()."<br />";
		#echo "Session: ".var_dump(\Session::all())."<br />";
		#echo "Post: ".var_dump($post)."<br />";
		#return;


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


		#echo \Lifecycle::dump();
	}


	/**
	 * Post Router
	 *
	 * @return Response
	 */
	public function postRouter($slug)
	{
		/*echo "POSTROUTER";

		$router = app('mrcore.router');
		if ($router->response == 301) {
			if (is_array($router->redirect)) {
				$url = route($router->redirect['route'], $router->redirect['params']).$router->redirect['query'];
			} else {
				$url = $router->redirect;
			}
			return Redirect::to($url);
		}*/


		/*
		#{{ $_SERVER['QUERY_STRING']; }}
		if (strlen($slug) == 36) {
			//URL is a guid, probably the post uuid
			$post = Post::where('uuid', '=', $slug)->select('id')->first();
			if (isset($post)) {
				return self::postRedirect($post->id);
			}
		}
		$route = Router::
			  where('slug', '=', $slug)
			->where('disabled', '=', false)
			->first(); #don't use ->select() or $route->save wont work
		if (isset($route)) {
			if (isset($route->post_id)) {
				//Load post content, stay on this url
				$route->clicks += 1;
				$route->save();
				return $this->showPost($route->post_id);
			} elseif (isset($route->url)) {
				//Redirect to this full url (could be external)
				$route->clicks += 1;
				$route->save();
				return Redirect::to($route->url);
			} else {
				throw new exception("Invalid router table entry for slug '$slug'");
			}
		} else {
			return Response::notFound();
		}*/
	}


	/**
	 * Redirect legecy or small URL to full post slug URL
	 *
	 * @return Response
	 */
	public function postRedirect($id = null, $slug = null)
	{


		
		/*$router = Mrcore::router();
		if ($router->response == 200) {
			return $this->showPost();
		
		} elseif ($router->response == 301) {
			// Redirect to proper URL
			if (is_array($router->redirect)) {
				$url = route($router->redirect['route'], $router->redirect['params']).$router->redirect['query'];
			} else {
				$url = $router->redirect;
			}
			return Redirect::to($url);
		
		} elseif ($router->response == 404) {
			return Response::notFound();
		
		} elseif ($router->response == 401) {
			return Response::denied();
		}*/
		

	}


	/**
	 * Shows the Home Post
	 *
	 * @return Response
	 */
	public function showHome()
	{
		#return $this->showPost(Config::get('mrcore.home'));
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