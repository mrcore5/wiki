<?php

use Illuminate\Database\Seeder;
use Mrcore\Wiki\Models\Role;
use Mrcore\Wiki\Models\UserRole;

class WikiRoleSeeder extends Seeder
{
	public function run()
	{
		DB::table('roles')->delete();

		// 1 Public
		Role::create(array(
			'name' => 'Public',
			'constant' => 'public'
		));

		// 2 Users
		Role::create(array(
			'name' => 'User',
			'constant' => 'user'
		));

		// User Roles (All users are in public and users)
		//Public
		UserRole::create(array('user_id' => 1, 'role_id' => 1)); #Anonymous
		UserRole::create(array('user_id' => 2, 'role_id' => 1)); #admin

		//Users
		UserRole::create(array('user_id' => 2, 'role_id' => 2)); #admin

	}
}
