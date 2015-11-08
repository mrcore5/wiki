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

        if (App::environment() === 'production') {
            exit('You cannot run the seeder in production');
        }

        Model::unguard();

		// Order is Critical
		$this->call('WikiUserSeeder');
        $this->call('WikiPostItemsSeeder');
        $this->call('WikiPostSeeder');
        $this->call('WikiRoleSeeder');
        $this->call('WikiPermissionSeeder');
        $this->call('WikiUserPermissionSeeder');
        $this->call('WikiPostPermissionSeeder');
        $this->call('WikiBadgeSeeder');
        $this->call('WikiTagSeeder');
        $this->call('WikiRouterSeeder');

    }

}

