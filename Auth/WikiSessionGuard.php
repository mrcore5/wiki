<?php namespace Mrcore\Wiki\Auth;

use Session;
use Illuminate\Auth\SessionGuard;

class WikiSessionGuard extends SessionGuard
{

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
            return ($user->id != config('mrcore.wiki.anonymous'));
        }
        return false;
    }

    /**
     * Check if user is super admin
     *
     * @return boolean
     */
    public function admin()
    {
        if (Session::has('user.admin')) {
            return Session::get('user.admin');
        }
        return false;
    }
}
