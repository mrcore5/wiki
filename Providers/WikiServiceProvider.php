<?php namespace Mrcore\Modules\Wiki\Providers;

use Auth;
use Event;
use Config;
use Layout;
use Mrcore;
use Module;
use Mrcore\Modules\Wiki\Models\Post;
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
	public function boot()
	{
		// Mrcore Module Tracking
		Module::trace(get_class(), __function__);

		// Define publishing rules
		$this->definePublishing();

		// Subscribe to Events
		Event::subscribe('UserEventHandler');		

		// Register additional css assets
		Layout::css('css/dataTables.bootstrap.css');
		Layout::css('css/wiki.css'); #should be last css

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
		// Mrcore Module Tracking
		Module::trace(get_class(), __function__);

		// Extend both Auth Guard and UserProvider
		$this->extendAuth();

		// Event Handler Bindings
		$this->app->bind('UserEventHandler', 'Mrcore\Modules\Wiki\Handlers\Events\UserEventHandler');
	}

	/**
	 * Define publishing rules
	 * 
	 * @return void
	 */
	private function definePublishing()
	{
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
	 * Extend both Auth Guard and UserProvider
	 * 
	 * @return void
	 */
	private function extendAuth()
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
	}

}
