<?php namespace Mrcore\Wiki\Handlers\Events;

use Auth;
use Config;
use Session;
use Carbon\Carbon;
use Mrcore\Auth\Models\User;
use Illuminate\Events\Dispatcher;

class UserEventHandler {

	/**
	 * Handle user login events.
	 */
	public function onUserLoggedIn($auth)
	{
		if ($auth->user->id != Config::get('mrcore.wiki.anonymous')) {
			// Save users permissions into session

			// Convert user into wiki user model
			$user = User::find($auth->user->id);
			$perms = $user->getPermissions();

			Session::put('user.admin', false);
			Session::put('user.perms', array());

			if (in_array('admin', $perms)) {
				//Super admin, don't save anything into user.perms, no need
				Session::put('user.admin', true);
			} else {
				Session::put('user.perms', $perms);
			}

			// Update last login date
			$user->login_at = Carbon::now();
			$user->save();
		}
	}

	/**
	 * Handle user logout events.
	 */
	public function onUserLoggedOut($auth)
	{
		// Application specific logout code here
		Session::forget('user');
	}

}
