<?php namespace Mrcore\Wiki\Http\Middleware;

use Auth;
use Closure;
use Response;

class AuthenticateMrcoreApp {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		#dump(Auth::user()); //YES!!
		#if (!Auth::admin()) {
		#	return Response::denied();
		#}

		return $next($request);
	}

}
