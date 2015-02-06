# mrcore-modules-wiki
Mrcore Wiki Module


# Installation

* add to providers array
* publish, migrate, seed

Add to your app/Console/Kernel.php $commands array
	'Mrcore\Modules\Wiki\Console\Commands\IndexPosts'



To Migrate and Seed

	./artisan vendor:publish --provider="Mrcore\Modules\Wiki\Providers\WikiServiceProvider" --tag="migrations" --force
	./artisan vendor:publish --provider="Mrcore\Modules\Wiki\Providers\WikiServiceProvider" --tag="seeds" --force
	composer dump-autoload

	Edit your database/seeds/DatabaseSeeder.php and add this to the end of the run() function:
	
	require __DIR__.'/WikiDatabaseSeeder.php';

	Then migrate with standard artisan commands:
	./artisan migrate
	./artisan db:seed
	or ./artisan migrate:refresh --seed


