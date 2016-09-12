<?php namespace Mrcore\Wiki\Database\Seeds;

use Illuminate\Database\Seeder;
use Mrcore\Wiki\Database\Seeds;
use Illuminate\Database\Eloquent\Model;

class WikiSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Production saftey
		if (app()->environment('production')) {
			exit('You cannot run the seeder in production');
		}

		// Allow mass assignment
		Model::unguard();

		// Order is critical

		// Auth Seeders
		$this->call(Seeds\AuthPermissionSeeder::class);

		// Wiki Seeders
		$this->call(Seeds\WikiPostItemsSeeder::class);
		$this->call(Seeds\WikiPostSeeder::class);
		$this->call(Seeds\WikiBadgeSeeder::class);
		$this->call(Seeds\WikiTagSeeder::class);
		$this->call(Seeds\WikiRouterSeeder::class);

	}

}
