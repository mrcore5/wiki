<?php

use Illuminate\Database\Seeder;
use Mrcore\Models\PostPermission;

class WikiPostPermissionSeeder extends Seeder
{
	public function run()
	{

		/**
		 * Post Permissions (what a role can do to a post)
		 */
		
		#1 Home
		PostPermission::create(array('post_id' => 1, 'role_id' => 1, 'permission_id' => 7)); # Public Read

		# Everything else is private

	}
}