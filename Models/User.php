<?php namespace Mrcore\Wiki\Models;

use DB;
use Auth;
use Config;
use Session;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Mrcore\Foundation\Support\Cache;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

	use Authenticatable, Authorizable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the remember me token for the user.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	/**
	 * Set the remember me token for the user.
	 *
	 */
	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	/**
	 * Get the remember me token name
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

	/**
	 * Find a model by its primary key.  Mrcore cacheable eloquent override.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public static function find($id, $columns = array('*'))
	{
		return Cache::remember(strtolower(get_class())."_$id", function() use($id, $columns) {
			return static::query()->find($id, $columns);
		});		
	}

	/**
	 * Get current logged in user
	 * @return Mrcore\Wiki\Models\User
	 */
	public static function currentUserTEST()
	{
		if (Auth::check()) {
			return Auth::user();
		} else {
			$user = User::find(Config::get('mrcore.wiki.anonymous'));
			Auth::login($user);
			Auth::user()->login();			
			#return self::find(Config::get('mrcore.wiki.anonymous'));
			return Auth::user();
		}
	}

	/**
	 * Get all roles linked to this user
	 *
	 * @return array of Role
	 */
	public function getRoles() {
		#obsolete, I don't wnat user roles, I want the permission those roles are linked to
		# so I just need a getPermissions once, store those constants in a small session array
		#$roles = $this->roles;

		#d($roles);

		#foreach ($roles as $role) {
		#	echo $role->name;
		#}
	}

	/**
	 * Get permissions for this user (not post permissions)
	 * 
	 * @return simple array of permission constants
	 */
	public function getPermissions()
	{
		/*
		SELECT
			DISTINCT p.constant
		FROM
			user_permissions up
			INNER JOIN permissions p on up.permission_id = p.id
		WHERE
			up.user_id = 2
		*/
		$userPermissions = DB::table('user_permissions')
			->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
			->where('user_permissions.user_id', '=', $this->id)
			->select('permissions.constant')
			->distinct()
			->get();

		// Convert results to single dimensional array of permission constants
		$perms = array();
		foreach ($userPermissions as $permission) {
			$perms[] = $permission->constant;
		}
		return $perms;
	}

	/**
	 * Check if user has this permission item (by permission constant)
	 * Uses the Session::get('user.perms') array set at login
	 *
	 * @return boolean
	 */
	public static function hasPermission($constant)
	{
		if (Auth::admin()) {
			return true;
		} else {
			if (Session::has('user.perms')) {
				if (in_array(strtolower($constant), Session::get('user.perms'))) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Check if user has this role constant
	 *
	 * @return boolean
	 */
	public function hasRole($constant) {
		#obsolete?? don't care, I care about permissions
		#required the function roles() to be enabled above, a many-to-many relationship
		#or query it yourself (probably better since I won't really use this function much?)
		
		#make a hasPermission which simply checks the existing session(user.perms) array
		#foreach ($this->roles as $role) {
		#	if (strtolower($constant) == strtolower($role->constant)) {
		#		return true;
		#	}
		#}
		#return false;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	#public function getReminderEmail()
	#{
	#	return $this->email;
	#}

}