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

class WikiServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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

        // Register publishers
        $this->registerPublishers();

        // Register migrations
        $this->registerMigrations();

        // Register Policies
        #$this->registerPolicies();

        // Register global and route based middleware
        $this->registerMiddleware($kernel, $router);

        // Register event listeners and subscriptions
        $this->registerListeners();

        // Register scheduled tasks
        #$this->registerSchedules();

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

        // Register facades and class aliases
        $this->registerFacades();

        // Register configs
        $this->registerConfigs();

        // Register services
        $this->registerServices();

        // Register artisan commands
        $this->registerCommands();

        // Register testing environment
        #$this->registerTestingEnvironment();

        // Register mrcore modules
        #$this->registerModules();
    }

    /**
     * Register facades and class aliases.
     *
     * @return void
     */
    protected function registerFacades()
    {
        // Register facades
        $facade = AliasLoader::getInstance();
        $facade->alias('Mrcore', \Mrcore\Wiki\Facades\Mrcore::class);
        #class_alias('Some\Long\Class', 'Short');
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
     * Register the application and other services.
     *
     * @return void
     */
    protected function registerServices()
    {
        // Register IoC bind aliases and singletons
        $this->app->alias(\Mrcore\Wiki\Api\Mrcore::class, \Mrcore\Wiki\Api\MrcoreInterface::class);
        $this->app->alias(\Mrcore\Wiki\Api\Config::class, \Mrcore\Wiki\Api\ConfigInterface::class);
        $this->app->alias(\Mrcore\Wiki\Api\Layout::class, \Mrcore\Wiki\Api\LayoutInterface::class);
        $this->app->alias(\Mrcore\Wiki\Api\Post::class, \Mrcore\Wiki\Api\PostInterface::class);
        $this->app->alias(\Mrcore\Wiki\Api\Router::class, \Mrcore\Wiki\Api\RouterInterface::class);
        $this->app->alias(\Mrcore\Wiki\Api\User::class, \Mrcore\Wiki\Api\UserInterface::class);
    }

    /**
     * Register artisan commands.
     * @return void
     */
    protected function registerCommands()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }
        $this->commands([
            \Mrcore\Wiki\Console\Commands\AppCommand::class,
            \Mrcore\Wiki\Console\Commands\IndexPosts::class,
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
     * Register mrcore modules
     *
     * @return void
     */
    public function registerModules()
    {
        // Register mrcore modules
        #Module::register('Mrcore\Other', true);
        #Module::loadViews('Mrcore\Other'); // If you need to use this modules view::
    }

    /**
     * Define the published resources and configs.
     *
     * @return void
     */
    protected function registerPublishers()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

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
     * Register the migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Register permission policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        // Define permissions (closure or Class@method)
        #Gate::define('update-post', function($user, $post) {
        #    return $user->id === $post->user_id;
        #});

        #Gate::before(function ($user, $ability) {
        #    if ($user->isSuperAdmin()) {
        #        return true;
        #    }
        #});
        # ->after() is also available

        // Or define an entire policy class
        #Gate::policy(\App\Post::class, \App\Policies\PostPolicy::class);
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

        /*
        Laravel 5.2 hack
        In L5.1, when you pushMiddleware from a provider level the middleware
        is pushed AFTER the FULL stack, so ever after app/Http/Kernel.php array
        So in L5.1 your middleware stack looked like:
            0 => "Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode"
            1 => "App\Http\Middleware\EncryptCookies"
            2 => "Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse"
            3 => "Illuminate\Session\Middleware\StartSession"
            4 => "Illuminate\View\Middleware\ShareErrorsFromSession"
            5 => "Mrcore\Wiki\Http\Middleware\AnalyzeRoute"

        BUT now in L5.2+ they changed the order.  So when you pushMiddleware from
        this provider level, it pushes BEFORE the Kernel.php is added, so you get
            0 => "Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode"
            1 => "Mrcore\Wiki\Http\Middleware\AnalyzeRoute"

        This means your customer middleware has NO Session:: and NO Auth::
        Which means my AnalyzeRoute wiki middleware is completely useless.

        So this hack fires up the Session and Auth middleware.  This will
        also allow Auth:: to be used in controller __construct which in L5.3
        this is not available.

        See https://github.com/laravel/framework/issues/15352
        See https://github.com/laravel/framework/issues/15072
        */
        #$kernel->pushMiddleware(\App\Http\Middleware\EncryptCookies::class);
        #$kernel->pushMiddleware(\Illuminate\Session\Middleware\StartSession::class);

        // So now my AnalyzeRoute has Auth:: access!
        $kernel->pushMiddleware(\Mrcore\Wiki\Http\Middleware\AnalyzeRoute::class);

        // Register route based middleware
        // FIXME Laravel version 5.3 vs 5.5 hack, remove when 5.3 is deprecated at dynatron
        $version = app()->version();
        if (substr($version, 0, 3) == '5.3') {
            $router->middleware('auth.admin', \Mrcore\Wiki\Http\Middleware\AuthenticateAdmin::class);
            $router->middleware('auth', \Mrcore\Wiki\Http\Middleware\Authenticate::class);
        } else {
            $router->aliasMiddleware('auth.admin', \Mrcore\Wiki\Http\Middleware\AuthenticateAdmin::class);
            $router->aliasMiddleware('auth', \Mrcore\Wiki\Http\Middleware\Authenticate::class);
        }

        // Authenticate mrcore applications and modules
        // Enable if you are testing laravel 5.3 new auth stuff
        $router->pushMiddlewareToGroup('web', \Mrcore\Wiki\Http\Middleware\AuthenticateApp::class);
    }

    /**
     * Register event listeners and subscriptions.
     *
     * @return void
     */
    protected function registerListeners()
    {
        // Login event listener
        Event::listen('Illuminate\Auth\Events\Login', function ($auth) {
            $handler = app('Mrcore\Wiki\Handlers\Events\UserEventHandler');
            $handler->onUserLoggedIn($auth);
        });

        // Logout event listener
        Event::listen('Illuminate\Auth\Events\Logout', function ($auth) {
            $handler = app('Mrcore\Wiki\Handlers\Events\UserEventHandler');
            $handler->onUserLoggedOut($auth);
        });
    }

    /**
     * Register the scheduled tasks
     *
     * @return void
     */
    protected function registerSchedules()
    {
        // Register all task schedules for this hostname ONLY if running from the schedule:run command
        /*if (app()->runningInConsole() && isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'schedule:run') {

            // Defer until after all providers booted, or the scheduler instance is removed from Illuminate\Foundation\Console\Kernel defineConsoleSchedule()
            $this->app->booted(function() {

                // Get the scheduler instance
                $schedule = app('Illuminate\Console\Scheduling\Schedule');

                // Define our schedules
                $schedule->call(function() {
                    echo "hi";
                })->everyMinute();

            });
        }*/
    }

    /**
     * Register mrcore layout overrides.
     *
     * @return void
     */
    protected function registerLayout()
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        // Register additional css and js assets with mrcore Layout
        Layout::css('css/wiki-bundle.css');
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
        Auth::extend('mrcore', function ($app, $name, array $config) {

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
