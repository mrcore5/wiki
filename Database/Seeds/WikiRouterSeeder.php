<?php namespace Mrcore\Wiki\Database\Seeds;

use Mrcore\Wiki\Models\Router;
use Illuminate\Database\Seeder;

class WikiRouterSeeder extends Seeder
{
	public function run()
	{

		// Create secondary optional routes to existing topics
		#Router::create(array('slug' => 'home2', 'post_id' => 1, 'static' => 1, 'default' => false));
		#Router::create(array('slug' => 'home3', 'post_id' => 1, 'static' => 1, 'default' => false, 'disabled' => true));

		/*
		Router::create(array(
			'slug' => 'google',
			'url' => 'http://google.com',
			'default' => false
		));

		Router::create(array(
			'slug' => 'dynatron/api/user',
			'post_id' => 3,
			'default' => false
		));
		*/


		// Create other routes
		#Router::create(array('slug' => 'google', 'url' => 'http://google.com', 'default' => false, 'static' => true));
		#Router::create(array('slug' => 'google2', 'url' => 'http://google.com', 'default' => false, 'static' => true, 'disabled' => true));

	}
}
