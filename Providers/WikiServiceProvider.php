<?php namespace Mrcore\Modules\Wiki\Providers;

use View;
use Auth;
use Config;
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

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Extend both Guard and EloquentUserProvider
		// This makes my own 'wiki' auth provider in config/app.php which 
		// enabled custom Auth::funtions() and caching on the user provider!
		Auth::extend('wiki', function() {
			// Guard extension found at https://laracasts.com/forum/?p=910-how-to-extend-auth/0
			$hash = $this->app->make('hash');
		    $model = Config::get('auth.model');
		    $session = $this->app->make('session.store');

			// Fire up standard EloquentUserProvider
			#$provider = new \Illuminate\Auth\EloquentUserProvider($hash, $model);
			$provider = new \Mrcore\Modules\Wiki\Auth\WikiUserProvider($hash, $model);
			
			// Fire up my custom Auth Provider as an extension to Laravels
			return new \Mrcore\Modules\Wiki\Auth\Guard($provider, $session);
		 });		
	}

}
