<?php namespace Mrcore\Modules\Wiki\Providers;

use View;
use Auth;
use Config;
use Layout;
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

		// Register additional css assets
		Layout::css('css/dataTables.bootstrap.css');
		Layout::css('css/wiki.css'); #should be last css

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

		// Add my own internal configs
		Config::set('mrcore.reserved_routes', array(
			'admin', 'router', 'file', 'files', 'search', 'auth', 'assets'
		));
		Config::set('mrcore.legacy_routes', array(
			'topic', 'topics', 'post', 'posts',
		));
		Config::set('mrcore.magic_folders', array('.sys', 'app'));
		Config::set('mrcore.magic_folders_exceptions', array('.sys/public', 'app/public'));

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Extend both Guard and EloquentUserProvider
		// This makes my own 'mrcore' auth provider in config/app.php which 
		// enabled custom Auth::funtions() and caching on the user provider!
		Auth::extend('mrcore', function() {
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

		// Register additional views
		// For wiki provider, this needs to be here instead of in boot()
		// So that it comes BEFORE the other ones in boot like Foundation
		View::addLocation(__DIR__.'/../Views');		
	}

}
