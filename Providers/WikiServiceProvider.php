<?php namespace Mrcore\Modules\Wiki\Providers;

use Auth;
use Event;
use Config;
use Layout;
use Mrcore;
use Module;
use Mrcore\Modules\Wiki\Models\Post;
use Illuminate\Foundation\AliasLoader;
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

		// Register additional css assets
		Layout::css('css/dataTables.bootstrap.css');
		Layout::css('css/wiki.css'); #should be last css

		// Add my own internal configs
		Config::set('mrcore.reserved_routes', array(
			'admin', 'router', 'file', 'files', 'search', 'auth', 'password', 'assets'
		));
		Config::set('mrcore.legacy_routes', array(
			'topic', 'topics', 'post', 'posts',
		));
		Config::set('mrcore.magic_folders', array('.sys', 'app'));
		Config::set('mrcore.magic_folders_exceptions', array('.sys/public', 'app/public'));

		// Login Event Listener
		Event::listen('auth.login', function($user) {
			$handler = app('Mrcore\Modules\Wiki\Handlers\Events\UserEventHandler');
			$handler->onUserLoggedIn($user);
		});

		// Logout Event Listener
		Event::listen('auth.logout', function($user) {
			$handler = app('Mrcore\Modules\Wiki\Handlers\Events\UserEventHandler');
			$handler->onUserLoggedOut($user);
		});
		
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

		// Register Facades
		$facade = AliasLoader::getInstance();
		$facade->alias('Mrcore', 'Mrcore\Modules\Wiki\Facades\Mrcore');

		// Mrcore Api Interface Aliases
		#$this->app->alias('Mrcore\Modules\Wiki\Api\Mrcore', 'mrcore'); #never used I believe, don't want it
		$this->app->alias('Mrcore\Modules\Wiki\Api\Mrcore', 'Mrcore\Modules\Wiki\Api\MrcoreInterface');
		$this->app->alias('Mrcore\Modules\Wiki\Api\Config', 'Mrcore\Modules\Wiki\Api\ConfigInterface');
		$this->app->alias('Mrcore\Modules\Wiki\Api\Layout', 'Mrcore\Modules\Wiki\Api\LayoutInterface');
		$this->app->alias('Mrcore\Modules\Wiki\Api\Post',   'Mrcore\Modules\Wiki\Api\PostInterface');
		$this->app->alias('Mrcore\Modules\Wiki\Api\Router', 'Mrcore\Modules\Wiki\Api\RouterInterface');
		$this->app->alias('Mrcore\Modules\Wiki\Api\User',   'Mrcore\Modules\Wiki\Api\UserInterface');		

		// Extend both Auth Guard and UserProvider
		$this->extendAuth();

		// Register Middleware
		$kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
		$kernel->pushMiddleware('Mrcore\Modules\Wiki\Http\Middleware\AnalyzeRoute');

		// Register our Artisan Commands
		$this->commands('Mrcore\Modules\Wiki\Console\Commands\IndexPosts');


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
