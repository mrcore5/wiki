<?php namespace Mrcore\Modules\Wiki\Http\Controllers;

use View;
use Input;
use Layout;
use Config;
use Request;
use Session;
use Redirect;
use Response;
use Mrcore\Modules\Wiki\Models\Tag;
use Mrcore\Modules\Wiki\Models\Type;
use Mrcore\Modules\Wiki\Models\Post;
use Mrcore\Modules\Wiki\Models\Badge;
use Mrcore\Modules\Wiki\Models\Format;

class SearchController extends Controller {

	/**
	 * Displays the search page
	 *
	 * @return Response
	 */
	public function search()
	{

		// Remove mobile viewport
		Layout::viewport('');

		// Get query


		# Dont use Request::segments here becuase it lumps multiple blank // into one
		#$query = Request::segments();
		#array_shift($query); #don't use
		#$query = implode("/", $query);
		# I want to keep the / because I use them in queries
		$query = substr(Request::path(), 7); #removes to leading search/


		// Custom queries
		if (is_numeric($query)) {
			// Query is an integer, redirect to that post id
			return Redirect::route('permalink', array($query));
		
		} elseif (starts_with($query, "/")) {
			// Query begins with /, so redirect to that given url
			return Redirect::route('url', $query);

		} elseif (starts_with($query, "hashtag:")) {
			// Query is hashtag lookup
			$query = substr($query, 8);
			$hashtag = Hashtag::find($query);
			if (isset($hashtag)) {
				$route = Router::findDefault($hashtag->route_id);
				if (isset($route)) {
					return Redirect::route('permalink', array($route->post_id));
				}
			}
			return Response::notFound();
		}


		// Get all types
		$types = Type::all();

		// Get all formats
		$formats = Format::all();

		// Get all badges
		$badges = Badge::all();
		
		// Get all tags
		$tags = Tag::all();

		// Sort options
		$sortOptions = array(
			'relevance' => 'Relevance',
			'updatednew' => 'Updated Date Newest',
			'updatedold' => 'Updated Date Oldest',
			'creatednew' => 'Created Date Newest',
			'createdold' => 'Created Date Oldest',
			'titleaz' => 'Title A-Z',
			'titleza' => 'Title Z-A',
			'mostviews' => 'Most Views',
		);

		// Manage view with a session
		$validViews = array('list', 'detail', 'sitemap');
		$defaultView = 'list';
		if (Input::has('view')) {
			$view = Input::get('view');
			Session::put('search.view', $view);
		} elseif (Session::has('search.view')) {
			$view = Session::get('search.view');
		} else {
			$view = $defaultView;
		}
		$view = strtolower($view);
		if (!in_array($view, $validViews)) $view = $defaultView;

		$selectedTags = array();
		foreach (Input::get() as $param) {
			if (preg_match('/tag(.*)/i', $param, $matches)) $selectedTags[] = $matches[1];
		}

		$posts = Post::getSearchPostsNew(Input::get());


		// Get Site and User Globals
		// // FIX ME these show up as mreschke then site global, should be reversed
		// need to make more permanent solution, this sucks
		// need to be on all pages like admin, router, search, login...fix it good
		#$post = Post::find(\Config::get('mrcore.wiki.global'));
		#Mrcore::post()->setModel($post);
		#$post->prepare();
		#$postContent = $post->content;

		$searchQuery = '';
		foreach (Input::all() as $key => $value) {
			if ($key == 'key') {
				$searchQuery .= $value.' ';	
			} else if($key == 'badge' || $key == 'format' || $key == 'type' || $key == 'tag') {
				$searchQuery .= $key.':'.$value.' ';
			}
		}

		return View::make("search.$view", array(
			'posts' => $posts,
			'searchQuery' => urldecode($searchQuery),
			'badges' => $badges,
			'tags' => $tags,
			'selectedTags' => $selectedTags,
			'types' => $types,
			'formats' => $formats,
			'sortOptions' => $sortOptions
		));
	}


	/**
	 * Get the search dropdown menu
	 * Handles via ajax only
	 */
	public function searchMenu()
	{
		// Ajax only controller
		if (!Request::ajax()) return Response::notFound();

		$post = Post::find(Config::get('mrcore.wiki.searchmenu'));
		if (!isset($post)) return Response::notFound();

		// Parse Post Now!
		$post->parse();

		$postContent = $post->content;
		return View::make("search.layout-searchbox", compact('postContent'));
	}

	/**
	 * Gets search results via ajax
	 * @return json
	 */
	public function ajaxSearch()
	{
		$posts = Post::getSearchPostsNew(Input::get());
		$ajaxPosts = new \stdClass();
		foreach ($posts as $post) {
			$ajaxPost = new \stdClass();
			$ajaxPost->id = $post->id;
			$ajaxPost->url = Post::route($post->id);			
			$ajaxPost->title = $post->title;
			$ajaxPosts->data[] = $ajaxPost;
		}

		return Response::json($ajaxPosts);
	}

}

