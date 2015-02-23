<?php namespace Mrcore\Modules\Wiki\Http\Middleware;

use Auth;
use Input;
use Config;
use Layout;
use Mrcore;
use Module;
use Closure;
use Redirect;
use Response;
use Mrcore\Modules\Wiki\Models\Post;

class AnalyzeRoute {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		#$isWebdav = preg_match("|".Config::get('mrcore.webdav_base_url')."|i", Request::url());
		#if ($isWebdav) {
		#	$this->responseCode = 202;
		#	return;
		#}

		// If not loggedin, login first time automatically as mrcore anonymous user
		// CANNOT use Auth::check() here becuase I overrode it to return false if using anonymous
		if (is_null(Auth::user())) Auth::loginUsingId(1);

		// Analyze Route
		$this->analyzeRoute();

		// Next middleware
		return $next($request);

	}

	private function analyzeRoute()
	{
		// Analyse URL and find matching mrcore router tabel entry
		$route = app('Mrcore\Modules\Wiki\Support\RouteAnalyzer');
		$route->analyzeUrl(Config::get('mrcore.reserved_routes'), Config::get('mrcore.legacy_routes'));

		if ($route->foundRoute()) {

			// Get post defined by route
			$post = Post::find($route->currentRoute()->post_id);

			// Check deleted
			if ($post->deleted && !Auth::admin()) $route->responseCode = 401;

			
			if ($route->responseCode == 200) {
				
				// Check post permissions including UUID
				if (!$post->uuidPermission()) $route->responseCode = 401;

				
				if ($route->responseCode == 200) {

					// Update clicks for post and router table
					$route->currentRoute()->incrementClicks($route);
					$post->incrementClicks();

					// Adjust $view for this $this->post
					Layout::title($post->title);
					if ($post->mode_id <> Config::get('mrcore.default_mode')) {
						Layout::mode($post->mode->constant);	
					}

					// Store post and router in the IoC for future usage
					Mrcore::post()->setModel($post);

					// Load App (workbench)
					if ($post->workbench) {
						$segments = explode("/", $post->workbench);
						if (count($segments) == 2) {
							$namespace = ''; $vendor = ''; $package = '';
							$vendor = $segments[0];
							$package = $segments[1];
							$namespace = studly_case($vendor) ."\\". studly_case($package);
							$path = str_replace("\\", "/", $namespace);
							#$namespace = "Mrcore\Apps\\$namespace";

							if (realpath(base_path()."/../Apps/$path")) {
								// Define app (as module array)
								$app = [
									'type' => 'app',
									'namespace' => "$namespace",
									'controller_namespace' => "$namespace\Http\Controllers",
									'provider' => "$namespace\Providers\\".studly_case($package)."ServiceProvider",
									'path' => "../Apps/$path",
									'routes' => "../Apps/$path/Http/routes.php",
									'route_prefix' => $route->currentRoute()->slug,
									'views' => "../Apps/$path/Views",
									'view_prefix' => $package,
									'assets' => "../Apps/$path/Assets",
									'enabled' => true,
								];

								// Dynamically add and register this module!
								Module::addModule('%app%', $app);
							}
						}
					}

				}
			}

	
		} elseif ($route->foundRedirect()) {
			#if (\Request::segment(1) == 'post' && \Request::segment(3) == 'edit') {
				// FIXME later, don't redirect if post/1/edit, or edit won't hit
			#} else {
			#	return Redirect::to($route->responseRedirect);	
			#}

		} elseif ($route->notFound()) {
			#abort(404);
			#Response::notFound(); ??
		}
		
		// Will also return 202 which means URL is reserved
		// and will be handled by laravel routes file, like /search, /login...

		// Set some Mrcore API data
		Mrcore::router()->setModel($route->currentRoute());
		Mrcore::router()->responseCode($route->responseCode);
		Mrcore::router()->responseRedirect($route->responseRedirect);
	}

}
