<?php

use Illuminate\Database\Seeder;
use Mrcore\Models\Permission;

class WikiPermissionSeeder extends Seeder
{
	public function run()
	{
		// Allow mass assignment
		Eloquent::unguard();

		DB::table('permissions')->delete();

		// User Permission Items
		Permission::create(array('name' => 'Create Posts', 'constant' => 'create'));            #1
		Permission::create(array('name' => 'Edit Posts', 'constant' => 'edit'));                #2
		Permission::create(array('name' => 'Comment on Posts', 'constant' => 'comment'));       #3
		Permission::create(array('name' => 'Super Admin', 'constant' => 'admin'));              #4
		Permission::create(array('name' => 'Write Script Code', 'constant' => 'write_script'));	#5
		Permission::create(array('name' => 'Write HTML Code', 'constant' => 'write_html'));		#6

		// Post Permission Items
		Permission::create(array('name' => 'Read Post', 'constant' => 'read',
			'user_permission' => false));						#6
		Permission::create(array('name' => 'Edit Post', 'constant' => 'write',
			'user_permission' => false));						#7
		Permission::create(array('name' => 'Comment on Post', 'constant' => 'comment',
			'user_permission' => false));						#8

	}

}