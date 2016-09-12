<?php namespace Mrcore\Wiki\Database\Seeds;

use Mrcore\Auth\Models\Role;
use Illuminate\Database\Seeder;
use Mrcore\Auth\Models\UserRole;
use Mrcore\Auth\Models\Permission;
use Mrcore\Auth\Models\UserPermission;

class AuthPermissionSeeder extends Seeder
{
	public function run()
	{
		$this->seedPermissions();
		$this->seedRoles();
		$this->seedUsers();
	}

	protected function seedPermissions()
	{
		// User Permission Items
		Permission::create(array('name' => 'Create Posts', 'constant' => 'create'));            #1
		Permission::create(array('name' => 'Edit Posts', 'constant' => 'edit'));                #2
		Permission::create(array('name' => 'Comment on Posts', 'constant' => 'comment'));       #3
		Permission::create(array('name' => 'Super Admin', 'constant' => 'admin'));              #4
		Permission::create(array('name' => 'Write Script Code', 'constant' => 'write_script'));	#5
		Permission::create(array('name' => 'Write HTML Code', 'constant' => 'write_html'));		#6

		// Post Permission Items
		Permission::create(array(
			'name' => 'Read Post',
			'constant' => 'read',
			'user_permission' => false)
		); //6

		Permission::create(array(
			'name' => 'Edit Post',
			'constant' => 'write',
			'user_permission' => false)
		); //7

		Permission::create(array('name' => 'Comment on Post', 'constant' => 'comment',
			'user_permission' => false));						#8
	}

	protected function seedRoles()
	{
		Role::create(array(
			'name' => 'Public',
			'constant' => 'public'
		));

		Role::create(array(
			'name' => 'User',
			'constant' => 'user'
		));
	}

	protected function seedUsers()
	{
		// User 1 - anonymous
		UserRole::create(array('user_id' => 1, 'role_id' => 1)); #public
		UserPermission::create(array('user_id' => 1, 'permission_id' => 3)); # comment

		// User 2 - admin
		UserRole::create(array('user_id' => 2, 'role_id' => 1)); #public
		UserRole::create(array('user_id' => 2, 'role_id' => 2)); #user

		UserPermission::create(array('user_id' => 2, 'permission_id' => 4)); # admin
	}

}
