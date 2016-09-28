<?php namespace Mrcore\Wiki\Http\Middleware;

use Auth;
use Closure;
use Response;

class AuthenticateAdmin
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::admin()) {
            return Response::denied();
        }

        return $next($request);
    }
}
