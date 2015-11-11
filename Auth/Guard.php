<?php namespace Mrcore\Wiki\Auth;

use Config;
use Session;

class Guard extends \Illuminate\Auth\Guard {

	/**
	 * Determine if the current user is authenticated.
	 *
	 * @return bool
	 */
	public function check()
	{
		// I override this for mrcore becuase all public access
		// is automatically logged in as the anonymous user, so technically
		// everyone is logged in.  So only auth check if not anonymous
		$user = $this->user();

		if (isset($user)) {
			return ($user->id != Config::get('mrcore.wiki.anonymous'));
		}
		return false;
		
		#return ! is_null($this->user());
	}

	/**
	 * Check if user is super admin
	 *
	 * @return boolean
	 */
	public static function admin()
	{
		if (Session::has('user.admin')) return Session::get('user.admin');
		return false;
	}


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