<?php namespace Mrcore\Wiki\Providers;

use Auth;
use Gate;
use Event;
use Layout;
use Module;
use Mrcore\Wiki\Models\Post;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\AliasLoader;
use Mrcore\Wiki\Auth\WikiUserProvider;
use Mrcore\Wiki\Auth\WikiSessionGuard;
use Illuminate\Support\ServiceProvider;

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
	public function boot(Kernel $kernel, Router $router)
	{
		// Mrcore Module Tracking
		Module::trace(get_class(), __function__);

		// Extend both auth guard and UserProvider
		$this->extendAuth();

		// Register resources
		$this->registerResources();

		// Register Policies
		$this->registerPolicies();

		// Register global and route based middleware
		$this->registerMiddleware($kernel, $router);

		// Register event listeners and subscriptions
		$this->registerListeners();

		// Register mrcore layout overrides
		$this->registerLayout();

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Mrcore module tracking
		Module::trace(get_class(), __function__);

		// Register facades
		$facade = AliasLoader::getInstance();
		$facade->alias('Mrcore', \Mrcore\Wiki\Facades\Mrcore::class);
		$facade->alias('Form', \Illuminate\Html\FormFacade::class);
		$facade->alias('Html', \Illuminate\Html\HtmlFacade::class);
		$facade->alias('Input', \Illuminate\Support\Facades\Input::class);

		// Register configs
		$this->registerConfigs();

		// Register IoC bind aliases
		$this->app->alias(\Mrcore\Wiki\Api\Mrcore::class, \Mrcore\Wiki\Api\MrcoreInterface::class);
		$this->app->alias(\Mrcore\Wiki\Api\Config::class, \Mrcore\Wiki\Api\ConfigInterface::class);
		$this->app->alias(\Mrcore\Wiki\Api\Layout::class, \Mrcore\Wiki\Api\LayoutInterface::class);
		$this->app->alias(\Mrcore\Wiki\Api\Post::class, \Mrcore\Wiki\Api\PostInterface::class);
		$this->app->alias(\Mrcore\Wiki\Api\Router::class, \Mrcore\Wiki\Api\RouterInterface::class);
		$this->app->alias(\Mrcore\Wiki\Api\User::class, \Mrcore\Wiki\Api\UserInterface::class);

		// Register other service providers
		$this->app->register(\Illuminate\Html\HtmlServiceProvider::class);

		// Register artisan commands
		$this->registerCommands();

		// Register testing environment
		$this->registerTestingEnvironment();
	}

	/**
	 * Define the resources used by mrcore.
	 *
	 * @return void
	 */
	protected function registerResources()
	{
		if (!$this->app->runningInConsole()) return;

		// App base path
		$path = realpath(__DIR__.'/../');

		// Config publishing rules
		// ./artisan vendor:publish --tag="mrcore.wiki.configs"
		$this->publishes([
			"$path/Config" => base_path('/config/mrcore'),
		], 'mrcore.wiki.configs');

		// Migration publishing rules
		// ./artisan vendor:publish --tag="mrcore.wiki.migrations"
		$this->publishes([
			"$path/Database/Migrations" => base_path('/database/migrations'),
		], 'mrcore.wiki.migrations');

		// Seed publishing rules
		// ./artisan vendor:publish --tag="mrcore.wiki.seeds"
		$this->publishes([
			"$path/Database/Seeds" => base_path('/database/seeds'),
		], 'mrcore.wiki.seeds');
	}

	/**
	 * Register permission policies.
	 *
	 * @return void
	 */
	public function registerPolicies()
	{
		//
	}

	/**
	 * Register global and route based middleware.
	 *
	 * @param Illuminate\Contracts\Http\Kernel $kernel
	 * @param \Illuminate\Routing\Router $router
	 * @return  void
	 */
	protected function registerMiddleware(Kernel $kernel, Router $router)
	{
		// Register global middleware
		$kernel->pushMiddleware('Mrcore\Wiki\Http\Middleware\AnalyzeRoute');

		// Register route based middleware
		$router->middleware('auth.admin', 'Mrcore\Wiki\Http\Middleware\AuthenticateAdmin');
	}

	/**
	 * Register event listeners and subscriptions.
	 *
	 * @return void
	 */
	protected function registerListeners()
	{
		// Login event listener
		Event::listen('Illuminate\Auth\Events\Login', function($auth) {
			$handler = app('Mrcore\Wiki\Handlers\Events\UserEventHandler');
			$handler->onUserLoggedIn($auth);
		});

		// Logout event listener
		Event::listen('Illuminate\Auth\Events\Logout', function($auth) {
			$handler = app('Mrcore\Wiki\Handlers\Events\UserEventHandler');
			$handler->onUserLoggedOut($auth);
		});
	}

	/**
	 * Register mrcore layout overrides.
	 *
	 * @return void
	 */
	protected function registerLayout()
	{
		if ($this->app->runningInConsole()) return;

		// Register additional css assets with mrcore Layout
		Layout::css('css/wiki-bundle.css');
	}

	/**
	 * Register additional configs and merges.
	 *
	 * @return void
	 */
	protected function registerConfigs()
	{
		// Append or overwrite configs
		config(['mrcore.wiki.reserved_routes' => ['admin', 'router', 'file', 'files', 'search', 'auth', 'password', 'assets']]);
		config(['mrcore.wiki.legacy_routes' => ['topic', 'topics', 'post', 'posts']]);
		config(['mrcore.wiki.magic_folders' => ['.sys', 'app']]);
		config(['mrcore.wiki.magic_folders_exceptions' => ['.sys/public', 'app/public']]);

		// Merge configs
		$this->mergeConfigFrom(__DIR__.'/../Config/wiki.php', 'mrcore.wiki');
	}

	/**
	 * Register artisan commands.
	 * @return void
	 */
	protected function registerCommands()
	{
		if (!$this->app->runningInConsole()) return;
		$this->commands([
			\Mrcore\Wiki\Console\Commands\AppCommand::class,
			\Mrcore\Wiki\Console\Commands\IndexPosts::class,
			\Mrcore\Wiki\Console\Commands\AppGitCommand::class,
			\Mrcore\Wiki\Console\Commands\AppMakeCommand::class
		]);
	}

	/**
	 * Register test environment overrides
	 *
	 * @return void
	 */
	public function registerTestingEnvironment()
	{
		// Register testing environment overrides
		if ($this->app->environment('testing')) {
			//
		}
	}

	/**
	 * Extend both auth guard and UserProvider
	 *
	 * @return void
	 */
	private function extendAuth()
	{
		// Extend both Guard and EloquentUserProvider
		// This makes my own 'mrcore' web guard in config/auth.php which
		// enabled custom Auth::funtions() and caching on the user provider!

		// Custom guard, which also uses a custom provider
		Auth::extend('mrcore', function($app, $name, array $config) {

			// Create custom wiki auth provider
			$hash = $app->make('hash');
			$model = config('auth.providers.users.model');
			$provider = new WikiUserProvider($hash, $model);

			// Or if you don't want to create a custom user provider you can
			// get the default one defined in your config like so:
			#$provider = Auth::createUserProvider($config['provider']);

			// Return our new auth guard and auth provider
			// I copied this from Illuminate\Auth\AuthManager.php createSessionDriver
			$guard = new WikiSessionGuard($name, $provider, $app['session.store']);
			$guard->setCookieJar($app['cookie']);
			$guard->setDispatcher($app['events']);
			$guard->setRequest($app->refresh('request', $guard, 'setRequest'));

			return $guard;
		});


	}

}
