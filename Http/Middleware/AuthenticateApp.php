<?php namespace Mrcore\Wiki\Http\Middleware;

use Auth;
use Layout;
use Mrcore;
use Request;
use Closure;
use Response;

class AuthenticateApp {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// This middleware is used only when an mrcore app is being used
		// All mrcore apps have a 'mrcore.app' middleware defined in Foundation/Support/Module.php loadRoutes() method.
		// This hits before any controller so this is where I have to
		// check post permissions since I cannot do it at the route
		// analyzer level as of Laravel 5.2+

		// If not logged in, login as anonymous user (a one time event)
		if (is_null(Auth::user())) Auth::loginUsingId(1);

		// Get router
		$router = Mrcore::router();

		// Get mrcore post
		$post = Mrcore::post();

		// If no post, we are not on a post or app (on /search or /auth/login... so continue middleware`)
		if (is_null($post->getModel())) return $next($request); 

		// Prepare post (parse, globals...)
		$post = $post->prepare(!Request::ajax());

		// Check if post is deleted
		if ($post->deleted && !Auth::admin()) return $this->denied();

		// Check post permissions
		// This includes checking for UUID public clinks
		if (!$post->uuidPermission()) return $this->denied();

		// Update clicks for post and router table
		$router->getModel()->incrementClicks();
		$post->incrementClicks();

		// Set browser title to posts title
		Layout::title($post->title);

		// Adjust layout mode
		if ($post->mode_id <> config('mrcore.wiki.default_mode')) Layout::mode($post->mode->constant);

		# Set bootstrap container based on post type
		// Apps have no container (full screen), all others are system default
		if ($post->type->constant == 'app') Layout::container(false);

		// Continue middleware
		return $next($request);
	}

	protected function denied()
	{
		// We cannot show a view at this point so Request::denied() does not work here
		// We can only redirect to a URL and pass the ?referer on a GET string
		if (Auth::check()) {
			// User logged in, but does not have access
			return abort(401);
		} else {
			// User not logged in, show login page
			$url = Request::url();
			$query = Request::server('QUERY_STRING');
			if ($query) {
				$query = "?".$query."&referer=$url";
			} else {
				$query = "?referer=$url";
			}			
			return redirect("auth/login$query");
		}

	}

}
