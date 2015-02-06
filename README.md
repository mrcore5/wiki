# mrcore-modules-wiki
Mrcore Wiki Module


# Installation

config/app.php provider order
foundation
auth
wiki

Auth must come before wiki, becuase it sets routes auth...and wiki routes have a catchall that will kill auth

* add in composer.json
* add to providers array
* change auth driver to 'mrcore' in config/auth.php
* publish, migrate, seed
* requires my custom config/mrcore.php
* Add to config/theme.php assets array

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


