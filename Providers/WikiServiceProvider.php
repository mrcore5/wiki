<?php namespace Mrcore\Modules\Wiki\Providers;

use View;
use Illuminate\Routing\Router;
use Mrcore\Modules\Foundation\Support\ServiceProvider;

class WikiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot(Router $router)
	{
		// Register additional views
		View::addLocation(__DIR__.'/../Views');

		// Register additional routes
		$router->group(['namespace' => 'Mrcore\Modules\Wiki\Http\Controllers'], function($router) {
			require __DIR__.'/../Http/routes.php';
		});		
		
		// Migration publishing rules
		// ./artisan vendor:publish --provider="Mrcore\Modules\Wiki\Providers\WikiServiceProvider" --tag="migrations" --force
		$this->publishes([
			__DIR__.'/../Database/Migrations' => base_path('/database/migrations'),
		], 'migrations');

		// Seed publishing rules
		// ./artisan vendor:publish --provider="Mrcore\Modules\Wiki\Providers\WikiServiceProvider" --tag="seeds" --force
		$this->publishes([
			__DIR__.'/../Database/Seeds' => base_path('/database/seeds'),
		], 'seeds');

		// To migrate and see mrcore
		// -------------------------
		// Simply run the two publish commands above then run composer dump-autoload.
		// Modify your database/seeds/DatabaseSeeder.php and add this to the end of the
		// run() function: require __DIR__.'/WikiDatabaseSeeder.php';
		// Then migrate with standard artisan commands:
		// ./artisan migrate
		// ./artisan db:seed
		// or ./artisan migrate:refresh --seed

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
