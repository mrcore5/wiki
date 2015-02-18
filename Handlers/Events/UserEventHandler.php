<?php namespace Mrcore\Modules\Wiki\Handlers\Events;

use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Events\Dispatcher;

class UserEventHandler {

	/**
	 * Handle user login events.
	 */
	public function onUserLoggedIn()
	{
		// Application specific login code here
		$user = Auth::user();

		// Save users permissions into session
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

	/**
	 * Handle user logout events.
	 */
	public function onUserLoggedOut()
	{
		// Application specific logout code here
		Session::forget('user');
	}

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  Illuminate\Events\Dispatcher $events
	 * @return array
	 */
	public function subscribe(Dispatcher $events)
	{
		$events->listen('Mrcore\Modules\Auth\Events\UserLoggedIn', 'UserEventHandler@onUserLoggedIn');
		$events->listen('Mrcore\Modules\Auth\Events\UserLoggedOut', 'UserEventHandler@onUserLoggedOut');
	}

}