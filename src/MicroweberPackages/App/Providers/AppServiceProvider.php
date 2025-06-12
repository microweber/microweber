<?php

namespace MicroweberPackages\App\Providers;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;
use MicroweberPackages\App\Console\Commands\ServeCodeCoverageTestCommand;
use MicroweberPackages\App\Console\Commands\ServeTestCommand;
use MicroweberPackages\App\Http\Middleware\AuthenticateSessionForUser;
use MicroweberPackages\App\Http\Middleware\TrimStrings;
use MicroweberPackages\App\Utils\Parser;
use MicroweberPackages\Console\Commands\AddLicenseKeyCommand;
use MicroweberPackages\Console\Commands\ModuleCommand;
use MicroweberPackages\Console\Commands\PackageInstallCommand;
use MicroweberPackages\Console\Commands\ReloadDatabaseCommand;
use MicroweberPackages\Console\Commands\UpdateCommand;
use MicroweberPackages\Core\Providers\CoreServiceProvider;
use MicroweberPackages\Dusk\DuskServiceProvider;
use MicroweberPackages\Filament\Providers\MicroweberFilamentRegistryServiceProvider;
use MicroweberPackages\Filament\Providers\MicroweberFilamentServiceProvider;
use MicroweberPackages\Helper\Format;
use MicroweberPackages\Install\Console\Commands\InstallCommand;
use MicroweberPackages\Install\MicroweberMigrator;
use MicroweberPackages\LaravelConfigExtended\ConfigExtendedServiceProvider;
use MicroweberPackages\Microweber\Providers\MicroweberServiceProvider;
use MicroweberPackages\Multilanguage\Http\Middleware\MultilanguageMiddleware;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Option\Console\Commands\OptionCommand;
use MicroweberPackages\Utils\Http\Http;
use MicroweberPackages\Utils\System\ClassLoader;
use Modules\Content\Models\Content;
use Modules\Media\Models\Media;


// Shop

if (!defined('MW_VERSION')) {
    include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'bootstrap.php';
}

class AppServiceProvider extends ServiceProvider
{

    protected $aliasInstance;

    /*
    * Application Service Providers...
    */
    public $laravel_providers = [
        \Illuminate\Translation\TranslationServiceProvider::class,

        \Illuminate\Auth\AuthServiceProvider::class,
        \Illuminate\Broadcasting\BroadcastServiceProvider::class,
        \Illuminate\Bus\BusServiceProvider::class,
        \Illuminate\Cache\CacheServiceProvider::class,
        \Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        \Illuminate\Cookie\CookieServiceProvider::class,
        \Illuminate\Database\DatabaseServiceProvider::class,
        \Illuminate\Encryption\EncryptionServiceProvider::class,
        \Illuminate\Filesystem\FilesystemServiceProvider::class,
        \Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        \Illuminate\Hashing\HashServiceProvider::class,
        \Illuminate\Mail\MailServiceProvider::class,
        \Illuminate\Notifications\NotificationServiceProvider::class,
        \Illuminate\Pagination\PaginationServiceProvider::class,
        \Illuminate\Pipeline\PipelineServiceProvider::class,
        \Illuminate\Queue\QueueServiceProvider::class,
        \Illuminate\Redis\RedisServiceProvider::class,
        \Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        \Illuminate\Session\SessionServiceProvider::class,
        \Illuminate\Validation\ValidationServiceProvider::class,
        \Illuminate\View\ViewServiceProvider::class,

//        \Illuminate\Session\SessionServiceProvider::class,
//        //  \Illuminate\Filesystem\FilesystemServiceProvider::class,
//        \Illuminate\Auth\AuthServiceProvider::class,
//        \Illuminate\Broadcasting\BroadcastServiceProvider::class,
//        \Illuminate\Bus\BusServiceProvider::class,
//        \Illuminate\Cache\CacheServiceProvider::class,
//        \Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
//        \Illuminate\Cookie\CookieServiceProvider::class,
//        \Illuminate\Database\DatabaseServiceProvider::class,
//        \Illuminate\Encryption\EncryptionServiceProvider::class,
//        \Illuminate\Foundation\Providers\FoundationServiceProvider::class,
//        \Illuminate\Hashing\HashServiceProvider::class,
//        \Illuminate\Mail\MailServiceProvider::class,
//        \Illuminate\Notifications\NotificationServiceProvider::class,
//        \MicroweberPackages\Pagination\PaginationServiceProvider::class,
//        \Illuminate\Pipeline\PipelineServiceProvider::class,
//        \Illuminate\Queue\QueueServiceProvider::class,
//        \Illuminate\Redis\RedisServiceProvider::class,
//        \Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
//        \Illuminate\Validation\ValidationServiceProvider::class,
//        \Illuminate\View\ViewServiceProvider::class,

    ];

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    public $laravel_aliases = [
//        'App' => \Illuminate\Support\Facades\App::class,
//        'Arr' => \Illuminate\Support\Arr::class,
//        'Artisan' => \Illuminate\Support\Facades\Artisan::class,
//        'Auth' => \Illuminate\Support\Facades\Auth::class,
//        'Blade' => \Illuminate\Support\Facades\Blade::class,
//        'Broadcast' => \Illuminate\Support\Facades\Broadcast::class,
//        'Bus' => \Illuminate\Support\Facades\Bus::class,
        //       'Cache' => \Illuminate\Support\Facades\Cache::class,
//        'Config' => \Illuminate\Support\Facades\Config::class,
//        'Cookie' => \Illuminate\Support\Facades\Cookie::class,
//        'Crypt' => \Illuminate\Support\Facades\Crypt::class,
//        'DB' => \Illuminate\Support\Facades\DB::class,
//        'Eloquent' => \Illuminate\Database\Eloquent\Models::class,
//        'Event' => \Illuminate\Support\Facades\Event::class,
//        //  'File' => \Illuminate\Support\Facades\File::class,
//        'Gate' => \Illuminate\Support\Facades\Gate::class,
//        'Hash' => \Illuminate\Support\Facades\Hash::class,
//        'Http' => \Illuminate\Support\Facades\Http::class,
//        'Lang' => \Illuminate\Support\Facades\Lang::class,
//        'Log' => \Illuminate\Support\Facades\Log::class,
//        'Mail' => \Illuminate\Support\Facades\Mail::class,
//        'Notification' => \Illuminate\Support\Facades\Notification::class,
//        'Password' => \Illuminate\Support\Facades\Password::class,
//        'Queue' => \Illuminate\Support\Facades\Queue::class,
//        'Redirect' => \Illuminate\Support\Facades\Redirect::class,
//        'Redis' => \Illuminate\Support\Facades\Redis::class,
//        'Request' => \Illuminate\Support\Facades\Request::class,
//        'Response' => \Illuminate\Support\Facades\Response::class,
//        'Route' => \Illuminate\Support\Facades\Route::class,
//        'Schema' => \Illuminate\Support\Facades\Schema::class,
//          'Session' => \Illuminate\Support\Facades\Session::class,
//        'Storage' => \Illuminate\Support\Facades\Storage::class,
//        'Str' => \Illuminate\Support\Str::class,
//        'URL' => \Illuminate\Support\Facades\URL::class,
//        'Validator' => \Illuminate\Support\Facades\Validator::class,
//        'View' => \Illuminate\Support\Facades\View::class,
//        'PDF' => \Barryvdh\DomPDF\Facade::class
    ];

    public function __construct($app)
    {
        //$this->loadPackagesComposerJson();

        ClassLoader::addDirectories([
            modules_path(),
            __DIR__,
        ]);

        ClassLoader::register();

        spl_autoload_register([$this, 'autoloadModules']);

        $this->aliasInstance = AliasLoader::getInstance();

        parent::__construct($app);
    }

    public function register()
    {
        //  return;
        //app()->usePublicPath(base_path());


        //  \Illuminate\Support\Facades\Vite::useBuildDirectory('build');

        $this->app->register(\Illuminate\Cache\CacheServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(\MicroweberPackages\Repository\Providers\RepositoryServiceProvider::class);
        //   $this->app->register( \MicroweberPackages\Cache\TaggableFileCacheServiceProvider::class);


//        $this->register(new ViewServiceProvider($this));
//        $this->register(new SessionServiceProvider($this));
//        $this->register(new FilesystemServiceProvider($this));
        //  $this->register(new TaggableFileCacheServiceProvider($this));
        $this->registerLaravelProviders();
        $this->registerLaravelAliases();

        $this->app->singleton('mw_migrator', function ($app) {
            $repository = $app['migration.repository'];
            return new MicroweberMigrator($repository, $app['db'], $app['files'], $app['events']);
        });

        //  $this->app->register(TranslationServiceProvider::class);


        if ($this->app->runningInConsole()) {
            $this->commands([
                ServeTestCommand::class,
                ServeCodeCoverageTestCommand::class,

            ]);

        }


       //$this->app->register(ConfigExtendedServiceProvider::class);

        //$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        if (app()->bound('debugbar')) {

            if (config('debugbar.enabled')) {
                $this->loadRoutesFrom(dirname(__DIR__) . '/routes/debugbar-routes.php');
                \Barryvdh\Debugbar\Facades\Debugbar::enable();
            } else {
                \Barryvdh\Debugbar\Facades\Debugbar::disable();
            }
        }

        $this->app->register(MicroweberFilamentRegistryServiceProvider::class);


        $this->app->register(CoreServiceProvider::class);

        $this->setEnvironmentDetection();
        $this->registerUtils();

        $this->registerSingletonProviders();


        $this->app->register(MicroweberServiceProvider::class);


        //    $this->registerHtmlCollective();
        //   $this->registerMarkdown();


//        $this->app->instance('config', new ConfigSave($this->app));
      //  $this->app->register(ConfigExtendedServiceProvider::class);

        if (!defined('ADMIN_PREFIX')) {
            define('ADMIN_PREFIX', mw_admin_prefix_url_legacy());
        }

        if (is_https() or (Config::get('microweber.force_https') && !is_cli())) {
             URL::forceScheme("https");
        }

        //  URL::forceRootUrl( site_url());
//
//        $is_installed = mw_is_installed();
//        if($is_installed) {
//            //asset_url config
//            if (!Config::get('app.asset_url')) {
//                Config::set('app.asset_url', site_url());
//            }
//        }

        $is_installed = mw_is_installed();
        //      $this->aliasInstance->alias('Carbon', 'Carbon\Carbon');


        if ($this->app->environment('local', 'testing')) {
            if (is_cli()) {
                // $this->app->register(CollisionServiceProvider::class);
                $this->app->register(DuskServiceProvider::class);
            }
        }

        if ($is_installed) {
            //load_all_service_providers_for_modules();
        }
        $this->app->register(MicroweberFilamentServiceProvider::class);


    }


    protected function registerLaravelProviders()
    {

        foreach ($this->laravel_providers as $provider) {
            $this->app->register($provider);
        }

        //  $this->app->bind('Illuminate\Contracts\Auth\Registrar', 'Microweber\App\Services\Registrar');

        $this->app->singleton(
            'Illuminate\Cache\StoreInterface',
            'Utils\Adapters\Cache\CacheStore'
        );

    }

    protected function registerLaravelAliases()
    {
        foreach ($this->laravel_aliases as $alias => $provider) {
            $this->aliasInstance->alias($alias, $provider);
        }
    }

    protected function setEnvironmentDetection()
    {

        if (isset($_ENV['APP_ENV'])) {
            $this->app->detectEnvironment(function () {
                return $_ENV['APP_ENV'];
            });
        }

        if ($this->app->runningUnitTests()) {
            $this->app->detectEnvironment(function () {
                return 'testing';
            });
        }


        if (isset($_SERVER['PHP_SELF']) and $_SERVER['PHP_SELF'] == 'vendor/phpunit/phpunit/phpunit') {
            $this->app->detectEnvironment(function () {
                return 'testing';
            });
        }

        if (isset($_SERVER['PHP_SELF']) and $_SERVER['PHP_SELF'] == 'artisan') {
            if (isset($_SERVER['argv'][1]) and $_SERVER['argv'][1] == 'dusk') {
                $this->app->detectEnvironment(function () {
                    return 'testing';
                });
            } else {

                $this->app->detectEnvironment(function () {
                    return app()->environment();
                });
            }
        }

//
//        if (defined('MW_UNIT_TEST')) {
//            $this->app->detectEnvironment(function () {
//                if (!defined('MW_UNIT_TEST_ENV_FROM_TEST')) {
//                    return 'testing';
//                }
//
//                return MW_UNIT_TEST_ENV_FROM_TEST;
//            });
//        }


        if (!is_cli()) {
            $domain = null;
            if (isset($_SERVER['HTTP_HOST'])) {
                $domain = $_SERVER['HTTP_HOST'];
            } else if (isset($_SERVER['SERVER_NAME'])) {
                $domain = $_SERVER['SERVER_NAME'];
            }


            return $this->app->detectEnvironment(function () use ($domain) {
//                if (getenv('APP_ENV')) {
//                    return getenv('APP_ENV');
//                }

                if (!$domain) {
                    return app()->environment();
                }

                $port = explode(':', $domain);

                $domain = str_ireplace('www.', '', $domain);

                if (isset($port[1])) {
                    $domain = str_ireplace(':' . $port[1], '', $domain);
                }

                if (is_dir(config_path($domain)) and is_file(config_path($domain) . '/microweber.php')) {
                    return strtolower($domain);
                }
                return app()->environment();


            });
        }


    }

    protected function registerUtils()
    {
        $this->app->bind('http', function ($app) {
            return new Http($app);
        });

        $this->app->singleton('format', function ($app) {
            return new Format();
        });

        $this->app->singleton('parser', function ($app) {
            return new Parser($app);
        });

        $this->app->singleton('browser_agent', function ($app) {
            return new Agent();
        });
    }

    protected function registerSingletonProviders()
    {
        $providers = [
            'ui' => 'Ui',
            'update' => 'UpdateManager',
            'cache_manager' => 'CacheManager',
            'config_manager' => 'ConfigurationManager',
            'notifications_manager' => 'NotificationsManager',
        //    'log_manager' => 'LogManager',
            'permalink_manager' => 'PermalinkManager',
            //    'layouts_manager' => 'LayoutsManager',
            'lang_helper' => 'Helpers\\Lang'
        ];

        foreach ($providers as $alias => $class) {
            $this->app->singleton($alias, function ($app) use ($class) {
                $class = 'MicroweberPackages\\App\Managers\\' . $class;

                return new $class($app);
            });
        }

//        $this->app->singleton('modules', function($app) use ($class) {
//           return new ModuleManager($app);
//        });

    }

    protected function registerHtmlCollective()
    {
//        $this->app->register('Collective\Html\HtmlServiceProvider');
//
//        $this->aliasInstance->alias('Form', 'Collective\Html\FormFacade');
//        $this->aliasInstance->alias('HTML', 'Collective\Html\HtmlFacade');
    }

    protected function registerMarkdown()
    {
        $this->app->register('GrahamCampbell\Markdown\MarkdownServiceProvider');

        $this->aliasInstance->alias('Markdown', 'GrahamCampbell\Markdown\Facades\Markdown');
    }

    public function boot(\Illuminate\Routing\Router $router)
    {
        View::addNamespace('app', __DIR__ . '/../resources/views');


        //   \Illuminate\Support\Facades\Vite::useBuildDirectory('build');

        if (defined('MW_SERVED_FROM_BASE_PATH')) {
             app()->usePublicPath(base_path().'/public');
            \Illuminate\Support\Facades\Vite::useBuildDirectory('public/build');
        }

        //set app.asset_url


//        if (!config::get('app.asset_url')) {
//            $this->app->singleton('url', function ($app) {
//                $routes = $app['router']->getRoutes();
//
//                // The URL generator needs the route collection that exists on the router.
//                // Keep in mind this is an object, so we're passing by references here
//                // and all the registered routes will be available to the generator.
//                $app->instance('routes', $routes);
//
//                return new UrlGenerator(
//                    $routes, $app->rebinding(
//                    'request', function ($app, $request) {
//                    $app['url']->setRequest($request);
//                }
//                ), site_url('public')
//                );
//            });
//        }


        $this->app->database_manager->add_table_model('content', Content::class);
        $this->app->database_manager->add_table_model('media', Media::class);

        if (is_cli()) {
            $this->commands('MicroweberPackages\Console\Commands\ResetCommand');
        }

        // If installed load module functions and set locale
        if (mw_is_installed()) {

            if (DB::Connection() instanceof \Illuminate\Database\SQLiteConnection) {

                DB::connection('sqlite')->getPdo()->sqliteCreateFunction('regexp',
                    function ($pattern, $data, $delimiter = '~', $modifiers = 'isuS') {
                        if (isset($pattern, $data) === true) {
                            return preg_match(sprintf('%1$s%2$s%1$s%3$s', $delimiter, $pattern, $modifiers), $data) > 0;
                        }
                        return;
                    }
                );

                DB::connection('sqlite')->getPdo()->sqliteCreateFunction('md5', 'md5');

//                // https://nik.software/sqlite-optimisations-in-laravel/
//                // https://gist.github.com/eusonlito/d8fc0462cf51fb8e89bde22c264a0c30
//                DB::connection('sqlite')
//                    ->statement('
//                PRAGMA journal_mode = WAL;
//                COMMIT;
//                ');

            }

            //    load_all_functions_files_for_modules();
            //load_all_service_providers_for_modules();
            //load_all_functions_files_for_modules();
            //load_service_providers_for_template();
            //load_functions_files_for_template();

            if(function_exists('is_lang_correct')) {
                $this->setupAppLocale();
            }
            if (is_cli()) {

                $this->commands(OptionCommand::class);
                $this->commands(UpdateCommand::class);
                $this->commands(ModuleCommand::class);
                $this->commands(ReloadDatabaseCommand::class);
                $this->commands(PackageInstallCommand::class);
                $this->commands(AddLicenseKeyCommand::class);

            }
        } else {
            // Otherwise register the install command
            $this->commands(InstallCommand::class);
        }

    //    $this->app->register(AdminRouteServiceProvider::class);

//        if (class_exists(\App\Providers\AppServiceProvider::class)) {
//            app()->register(\App\Providers\AppServiceProvider::class);
//        }

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/web.php');

        if (mw_is_installed()) {
            $this->app->event_manager->trigger('mw.after.boot', $this);
        }

        // >>> MW Kernel add
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\MicroweberPackages\App\Http\Middleware\TrustProxies::class);
        //    $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware( \Illuminate\Http\Middleware\TrustProxies::class);
     //   $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Fruitcake\Cors\HandleCors::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\MicroweberPackages\App\Http\Middleware\CheckForMaintenanceMode::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\MicroweberPackages\App\Http\Middleware\TrimStrings::class);
       // $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Session\Middleware\StartSession::class);
        //   $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\MicroweberPackages\App\Http\Middleware\StartSessionExtended::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class);
      $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);

      //  $router->pushMiddlewareToGroup('web', AuthenticateSessionForUser::class);
        $router->pushMiddlewareToGroup('web', \Illuminate\Session\Middleware\StartSession::class);
        $router->pushMiddlewareToGroup('web', \Illuminate\Cookie\Middleware\EncryptCookies::class);
        $router->pushMiddlewareToGroup('web', \Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $router->pushMiddlewareToGroup('web', AuthenticateSession::class);
        // $router->pushMiddlewareToGroup('web', \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class);
        $router->pushMiddlewareToGroup('web', \Illuminate\Routing\Middleware\SubstituteBindings::class);
       // AddQueuedCookiesToResponse::class,

        $router->aliasMiddleware('auth', \MicroweberPackages\App\Http\Middleware\Authenticate::class);
        $router->aliasMiddleware('auth.basic', \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class);
        $router->aliasMiddleware('bindings', \Illuminate\Routing\Middleware\SubstituteBindings::class);
        $router->aliasMiddleware('cache.headers', \Illuminate\Http\Middleware\SetCacheHeaders::class);
        $router->aliasMiddleware('can', \Illuminate\Auth\Middleware\Authorize::class);
        $router->aliasMiddleware('guest', \MicroweberPackages\App\Http\Middleware\RedirectIfAuthenticated::class);
        $router->aliasMiddleware('password.confirm', \Illuminate\Auth\Middleware\RequirePassword::class);
        $router->aliasMiddleware('signed', \Illuminate\Routing\Middleware\ValidateSignature::class);
        $router->aliasMiddleware('throttle', \MicroweberPackages\App\Http\Middleware\ThrottleExternalRequests::class);
        $router->aliasMiddleware('verified', \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class);
        $router->aliasMiddleware('xss', \MicroweberPackages\App\Http\Middleware\XSS::class);
        $router->aliasMiddleware('remove_html', \MicroweberPackages\App\Http\Middleware\RemoveHtml::class);
    //    $router->aliasMiddleware('admin', \MicroweberPackages\Admin\Http\Middleware\Admin::class);
        $router->aliasMiddleware('api_auth', \MicroweberPackages\App\Http\Middleware\ApiAuth::class);
        $router->aliasMiddleware('allowed_ips', \MicroweberPackages\App\Http\Middleware\AllowedIps::class);


        $router->pushMiddlewareToGroup('admin', \Illuminate\Session\Middleware\StartSession::class);
        $router->pushMiddlewareToGroup('admin', \Illuminate\Cookie\Middleware\EncryptCookies::class);
        $router->pushMiddlewareToGroup('admin', \Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $router->pushMiddlewareToGroup('admin', AuthenticateSession::class);
        $router->pushMiddlewareToGroup('admin', \MicroweberPackages\Admin\Http\Middleware\Admin::class);


        //$router->pushMiddlewareToGroup('api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);

        $router->pushMiddlewareToGroup('api', \Illuminate\Session\Middleware\StartSession::class);
        $router->pushMiddlewareToGroup('api', \Illuminate\Cookie\Middleware\EncryptCookies::class);
        $router->pushMiddlewareToGroup('api', \Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $router->pushMiddlewareToGroup('api', AuthenticateSession::class);


        $router->middlewareGroup('public.web', [
            'xss',
            TrimStrings::class,
            MultilanguageMiddleware::class,
            // AuthenticateSessionForUser::class,
        ]);

        $router->middlewareGroup('api', [
            'xss',

            TrimStrings::class,
            // 'throttle:1000,1',
            // 'api_auth'
        ]);

        $router->middlewareGroup('api.user', [
            'xss',
            TrimStrings::class,
            // 'throttle:1000,1',
            'api_auth'
        ]);
        $router->middlewareGroup('api.public', [
            'xss',
            TrimStrings::class,
            //   'throttle:1000,1'
        ]);
        $router->middlewareGroup('api.static', [
            \MicroweberPackages\App\Http\Middleware\SessionlessMiddleware::class,
            \Illuminate\Http\Middleware\CheckResponseForModifications::class
        ]);


        // <<< MW Kernel add


        $this->app->terminating(function () {
            // possible fix of mysql error https://github.com/laravel/framework/issues/18471
            // user already has more than 'max_user_connections' active connections
            DB::disconnect();
        });
    }

    public function autoloadModules($className)
    {
        $filename = modules_path() . $className . '.php';
        $filename = normalize_path($filename, false);

        if (!class_exists($className, false) && is_file($filename)) {
            require_once $filename;
        }
    }



//    /**
//     * @deprecated
//     */
//    public function loadPackagesComposerJson()
//    {
//        $dir = dirname(dirname(__DIR__));
//
//        $cached = base_path('bootstrap/cache/microweber_packages.json');
//        if (is_file($cached) and (time() - filemtime($cached) >= 15 * 60)) { // 30 minutes ago
//            $cache_content = json_decode(file_get_contents($cached), TRUE);
//            if (is_array($cache_content) && !empty($cache_content)) {
//                foreach ($cache_content as $file) {
//                    $file_path_inlude = $dir . $file;
//                    if (is_file($file_path_inlude)) {
//                        require_once($file_path_inlude);
//                    }
//                }
//                return;
//            }
//        }
//
//        $files_map = [];
//        $packages = glob("$dir/*/composer.json");
//
//        //find a php file in each folder and get its realpath
//        foreach ($packages as $filename) {
//
//            $phpfile = realpath($filename);
//            $cont = @json_decode(@file_get_contents($phpfile), 1);
//
//            if (isset($cont['autoload'])) {
//                if (isset($cont['autoload']['files']) and is_array($cont['autoload']['files']) and !empty($cont['autoload']['files'])) {
//                    foreach ($cont['autoload']['files'] as $file) {
//                        $package_dir = dirname($phpfile) . DS;
//                        $file_path = $package_dir . $file;
//                        if (is_file($file_path)) {
//                            $file_path_save = $file_path;
//                            $file_path_save = str_replace($dir, '', $file_path_save);
//                            $files_map[] = $file_path_save;
//
//                            require_once($file_path);
//                        }
//                    }
//                }
//            }
//
//        }
//
//        if (!empty($files_map)) {
//            file_put_contents($cached, json_encode($files_map));
//        }
//    }

    private function setupAppLocale()
    {
        $isLocaleChangedFromMultilanguageLogics = false;

        $currentUri = request()->path();
        $langCookie = request()->cookie('lang');

        //  Change language if user request language with LINK has lang abr
        if (MultilanguageHelpers::multilanguageIsEnabled()) {

            $localeIsChangedFromGetRequest = false;
            $locale = request()->get('locale');
            if (is_lang_correct($locale)) {
                $localeIsChangedFromGetRequest = true;
                $isLocaleChangedFromMultilanguageLogics = true;
                // $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($locale);
                $localeSettings = app()->multilanguage_repository->getSupportedLocale($locale);
                if (!empty($localeSettings) && isset($localeSettings['is_active']) && $localeSettings['is_active'] == 'y') {

                    change_language_by_locale($locale, true);
                }
            }

            // if locale is not changed from get request we must to chek URL SLUGS
            if (!$localeIsChangedFromGetRequest) {
                $linkSegments = url_segment(-1, $currentUri);
                $linkSegments = array_filter($linkSegments, 'trim');

                if (!empty($linkSegments)) {
                    if (isset($linkSegments[0]) and $linkSegments[0]) {
                        $skip_items = ['api', 'token', '_token'];

                        if (!in_array($linkSegments[0], $skip_items)) {
                            $localeSettings = app()->multilanguage_repository->getSupportedLocale($linkSegments[0]);

                            if ($localeSettings and isset($localeSettings['locale']) && isset($localeSettings['is_active'])) {
                                $isLocaleChangedFromMultilanguageLogics = true;

                                $needToChangeLocale = true;
                                $needToChangeLocaleCookie = true;
                                if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang'])) {
                                    if ($_COOKIE['lang'] == $localeSettings['locale']) {
                                        $needToChangeLocaleCookie = false;
                                    }
                                }
                                //   $needToChangeLocale = true;
                                if ($needToChangeLocale) {
                                    change_language_by_locale($localeSettings['locale'], $needToChangeLocaleCookie);
                                }
                            }
                        }
                    }
                }
            }
        }

        // If locale is not changed from link
        if (!$isLocaleChangedFromMultilanguageLogics) {

            // If we have a lang cookie read from theere
            if (isset($langCookie) && ($langCookie)) {
                $setCurrentLangTo = $langCookie;
            } else {
                if (MultilanguageHelpers::multilanguageIsEnabled()) {
                    // Set from default homepage lang settings
                    $setCurrentLangTo = get_option('homepage_language', 'website');
                } else {
                    // Set from default language language settings
                    $setCurrentLangTo = get_option('language', 'website');
                }
            }


            if ($setCurrentLangTo && is_lang_correct($setCurrentLangTo)) {
                if (MultilanguageHelpers::multilanguageIsEnabled()) {

                    $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($setCurrentLangTo);
                    if (!empty($localeSettings) && isset($localeSettings['is_active']) && $localeSettings['is_active'] == 'y') {
                        set_current_lang($setCurrentLangTo);
                    }
                } else {
                    set_current_lang($setCurrentLangTo);
                }
            }
        }
    }
}
