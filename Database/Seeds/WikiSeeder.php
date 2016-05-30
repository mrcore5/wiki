<?php

use Illuminate\Database\Seeder;
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
		$this->call('WikiPostItemsSeeder');
		$this->call('WikiPostSeeder');
		$this->call('WikiBadgeSeeder');
		$this->call('WikiTagSeeder');
		$this->call('WikiRouterSeeder');

	}

}
