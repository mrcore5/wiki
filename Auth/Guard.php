<?php namespace Mrcore\Modules\Wiki\Auth;

#\Illuminate\Auth\EloquentUserProvider {

class Guard extends \Illuminate\Auth\Guard {

	/**
	 * Get the currently authenticated user.
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function userXXX()
	{
		//don't call Auth::check or $this->check here, its recursive :)
		//
	}


	/**
	 * Get the ID for the currently authenticated user.
	 *
	 * @return int|null
	 */
	public function idXXX()
	{
		// for wiki return 1, anon if not logged in

	}


}