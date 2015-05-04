<?php namespace Mrcore\Modules\Wiki\Http\Controllers\Admin;

use DB;
use View;
use Input;
use Config;
use Request;
use Hash;
use stdClass;
use Response;
use Carbon\Carbon;
use Mrcore\Models\User;
use Mreschke\Helpers\String;
use Mrcore\Modules\Wiki\Models\Role;
use Mrcore\Modules\Wiki\Models\UserRole;
use Mrcore\Modules\Wiki\Models\Permission;
use Mrcore\Modules\Wiki\Models\UserPermission;
use Mrcore\Modules\Wiki\Http\Controllers\Controller;

class UserController extends Controller {

	/**
	 * Displays a DataTable
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = new stdClass();
		$data->name = 'User';
		$data->partial = '_users';
		$data->dataUrl = 'user/data';
		
		return View::make('admin.content', compact('data'));
	}

	/**
	 * Retrieves Data for the DataTable
	 * @return json
	 */
	public function getData()
	{
		$items = User::select(array('id as ID', DB::raw('CONCAT(first, " ", last) as User'), 'avatar as Avatar', 'email as Email', 'disabled as Disabled', 'login_at as LastLogin'))->get();
		foreach($items as $item) {
			$item->User = '<img src="/assets/uploads/'.$item->Avatar.'" /> <div>' . $item->User.'</div>';
			unset($item->Avatar);
			$item->LastLogin = (new Carbon($item->LastLogin))->toDateTimeString();
			$item->Action = '<button class="btn btn-sm btn-warning btn-edit"><i class="fa fa-edit"></i></button>
							 <button class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i>
							</button>';			
		}

		return $items;
	}

	/**
	 * Retrieves Data for a User
	 * @return json
	 */
	public function getUserData($userID)
	{
		$roles = Role::all();
		$permissions = Permission::where('user_permission', 1)->get();
		
		$userRoles = UserRole::where('user_id', $userID)->get();
		foreach ($roles as $role) {
			$role->set = false;
			if (sizeOf($userRoles->where('role_id', $role->id)) > 0) {
				$role->set = true;
			}
		}

		$userPermissions = UserPermission::where('user_id', $userID)->get();
		foreach ($permissions as $permission) {
			$permission->set = false;
			if (sizeOf($userPermissions->where('permission_id', $permission->id)) > 0) {
				$permission->set = true;
			}
		}

		$columns = [
			'alias',
			'first',
			'last',
			'email',
			'disabled',
			'global_post_id',
			'home_post_id',				
		];

		$user = User::select($columns)->find($userID);

		return [
			"user" => $user,
			"roles" => $roles,
			"permissions" => $permissions,
		];
	}

	/**
	 * Adds a new entry
	 * @return response
	 */
	public function store()
	{
		$email = Input::get('email');
		$alias = Input::get('alias');
		$first = Input::get('first');
		$last = Input::get('last');

		$password = Input::get('password');
		$global_post_id = Input::get('global_post_id');
		$home_post_id = Input::get('home_post_id');
		$disabled = Input::get('disabled');

		$user = new User;
		
		$user->password = Hash::make($password);
		$user->email = $email;
		$user->alias = $alias;
		$user->first = $first;
		$user->last = $last;
		$user->uuid = String::getGuid();
		$user->global_post_id = ($global_post_id) ?: null;
		$user->home_post_id = ($home_post_id) ?: null;
		$user->disabled = (isset($disabled)) ?: 0;
		
		$user->save();
		$user->id;

		if (Input::hasFile('avatar')) {
			$file = Input::file('avatar');
			file_put_contents(
				base_path()."/public/uploads/avatar_user".$user->id.".png",
				$file
			);
			$user->avatar = 'avatar_user'.$user->id.'.png';
			$user->save();
		}

		// Add Roles
		if (Input::get('roles')) {
			foreach (Input::get('roles') as $role) {
				$userRole = new UserRole();
				$userRole->role_id = $role;
				$userRole->user_id = $user->id;
				$userRole->save();
			}
		}

		// Add Permissions
		if (Input::get('permissions')) {
			foreach (Input::get('permissions') as $permission) {
				$userPermission = new UserPermission();
				$userPermission->permission_id = $permission;
				$userPermission->user_id = $user->id;
				$userPermission->save();
			}
		}

		return Response::json([
			'message' => 'Success! User Added!'
		], 200);
	}

	/**
	 * Updates an existing entry
	 * @return response
	 */
	public function update()
	{
		$id = Input::get('id');
		$email = Input::get('email');
		$alias = Input::get('alias');
		$first = Input::get('first');
		$last = Input::get('last');
		$password = Input::get('password');
		$global_post_id = Input::get('global_post_id');
		$home_post_id = Input::get('home_post_id');
		$disabled = Input::get('disabled');
		
		if ($id != 0) {
			$user = User::find($id);	

			if (isset($password) && sizeOf($password > 0) && $password != '') {
				$user->password = Hash::make($password);
			}
			
			$user->email = $email;
			$user->alias = $alias;
			$user->first = $first;
			$user->last = $last;
			$user->global_post_id = ($global_post_id) ?: null;
			$user->home_post_id = ($home_post_id) ?: null;
			$user->disabled = (isset($disabled)) ?: 0;
			
			if (Input::hasFile('avatar')) {
				$file = Input::file('avatar');
				file_put_contents(
					base_path()."/public/uploads/avatar_user".$user->id.".png",
					$file
				);
				$user->avatar = 'avatar_user'.$user->id.'.png';			
			}

			$user->save();

			// Update Roles
			UserRole::where('user_id', $id)->delete();
			if (Input::get('roles')) {
				foreach (Input::get('roles') as $role) {
					$userRole = new UserRole();
					$userRole->role_id = $role;
					$userRole->user_id = $user->id;
					$userRole->save();
				}
			}

			// Update Permissions
			UserPermission::where('user_id', $id)->delete();
			if (Input::get('permissions')) {
				foreach (Input::get('permissions') as $permission) {
					$userPermission = new UserPermission();
					$userPermission->permission_id = $permission;
					$userPermission->user_id = $user->id;
					$userPermission->save();
				}
			}

			return Response::json([
				'message' => 'Success! User Updated!'
			], 200);
		} else {
			return Response::json([
				'message' => 'Error! Update Failed!'
			], 400);
		}
	}

	/**
	 * Deletes an existing entry
	 * @return response
	 */
	public function destroy()
	{
		$id = Input::get('id');
		
		if ($id != 0) {
			UserRole::where('user_id', $id)->delete();
			UserPermission::where('user_id', $id)->delete();
			$user = User::find($id);
			$user->delete();

			return Response::json([
				'message' => 'Success! User Deleted!'
			], 200);
		} else {
			return Response::json([
				'message' => 'Error! Delete Failed!'
			], 400);
		}
	}

}

