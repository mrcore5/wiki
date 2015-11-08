<?php

use Mreschke\Helpers\String;
use Illuminate\Database\Seeder;
use Mrcore\Modules\Wiki\Models\User;

class WikiUserSeeder extends Seeder
{

	public function run()
	{
		DB::table('users')->delete();
		DB::table('user_roles')->delete();

		// 1 Anonymous User
		User::create(array(
			'uuid'     => String::getGuid(),
			'email'    => 'anonymous@anonymous.com',
			'password' => Hash::make(md5(microtime())),
			'first'    => 'Anonymous',
			'last'     => 'Anonymous',
			'alias'    => 'anonymous',
			'avatar'   => 'avatar_user1.png',
			'global_post_id' => null,
			'home_post_id'   => null,
			'login_at'  => '1900-01-01 00:00:00',
			'disabled'       => true
		));

		// 2 Admin
		User::create(array(
			'uuid'     => String::getGuid(),
			'email'    => 'mail@mreschke.com',
			'password' => Hash::make('password'),
			'first'    => 'Admin',
			'last'     => 'Istrator',
			'alias'    => 'admin',
			'avatar'   => 'avatar_user2.png',
			'global_post_id' => 4,
			'home_post_id'   => 3,
			'login_at'  => '1900-01-01 00:00:00',
			'disabled'       => false
		));

	}

}
