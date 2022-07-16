<?php

namespace MicroweberPackages\App\Providers;

use Hamcrest\Core\Is;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;
use Laravel\Dusk\DuskServiceProvider;
use MicroweberPackages\App\Console\Commands\ServeTestCommand;
use MicroweberPackages\Admin\AdminServiceProvider;
use MicroweberPackages\App\Managers\Helpers\Lang;
use MicroweberPackages\App\Utils\Parser;

use MicroweberPackages\Backup\Providers\BackupServiceProvider;
use MicroweberPackages\Blog\BlogServiceProvider;
use MicroweberPackages\Comment\CommentServiceProvider;
use MicroweberPackages\Config\ConfigSaveServiceProvider;
use MicroweberPackages\ContentFilter\Providers\ContentFilterServiceProvider;
use MicroweberPackages\Core\CoreServiceProvider;
use MicroweberPackages\Customer\Providers\CustomerEventServiceProvider;
use MicroweberPackages\Customer\Providers\CustomerServiceProvider;
use MicroweberPackages\Install\InstallServiceProvider;
use MicroweberPackages\Livewire\LivewireServiceProvider;
use MicroweberPackages\Media\Models\Media;
use MicroweberPackages\Multilanguage\Http\Middleware\MultilanguageMiddleware;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\MultilanguageServiceProvider;
use MicroweberPackages\Notification\Providers\NotificationServiceProvider;
use MicroweberPackages\Offer\Providers\OfferServiceProvider;
use MicroweberPackages\Order\Providers\OrderEventServiceProvider;
use MicroweberPackages\Queue\Providers\QueueEventServiceProvider;
use MicroweberPackages\Queue\Providers\QueueServiceProvider;
use MicroweberPackages\Repository\Providers\RepositoryEventServiceProvider;
use MicroweberPackages\Repository\Providers\RepositoryServiceProvider;
use MicroweberPackages\Shipping\ShippingManagerServiceProvider;
use MicroweberPackages\Translation\Providers\TranslationServiceProvider;
use MicroweberPackages\User\Providers\UserEventServiceProvider;
use MicroweberPackages\Cart\Providers\CartEventServiceProvider;
use MicroweberPackages\User\Providers\UserServiceProvider;
use MicroweberPackages\Category\Providers\CategoryEventServiceProvider;
use MicroweberPackages\Category\Providers\CategoryServiceProvider;
use MicroweberPackages\Config\ConfigSave;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\ContentManagerServiceProvider;
use MicroweberPackages\Content\ContentServiceProvider;
use MicroweberPackages\ContentData\Providers\ContentDataEventServiceProvider;
use MicroweberPackages\ContentData\Providers\ContentDataServiceProvider;
use MicroweberPackages\Country\CountryServiceProvider;
use MicroweberPackages\CustomField\Providers\CustomFieldServiceProvider;
use MicroweberPackages\CustomField\Providers\CustomFieldEventServiceProvider;
use MicroweberPackages\Database\DatabaseManagerServiceProvider;
use MicroweberPackages\Event\EventManagerServiceProvider;
use MicroweberPackages\FileManager\FileManagerServiceProvider;
use MicroweberPackages\Form\Providers\FormServiceProvider;
use MicroweberPackages\Helper\Format;
use MicroweberPackages\Helper\HelpersServiceProvider;
use MicroweberPackages\Install\MicroweberMigrator;
use MicroweberPackages\Media\MediaManagerServiceProvider;
use MicroweberPackages\Menu\Providers\MenuEventServiceProvider;
use MicroweberPackages\Menu\Providers\MenuServiceProvider;
use MicroweberPackages\Module\ModuleServiceProvider;

use MicroweberPackages\OpenApi\Providers\SwaggerServiceProvider;
use MicroweberPackages\Option\Providers\OptionServiceProvider;

// Shop
use MicroweberPackages\Cart\CartManagerServiceProvider;
use MicroweberPackages\Checkout\CheckoutManagerServiceProvider;
use MicroweberPackages\Currency\CurrencyServiceProvider;
use MicroweberPackages\Order\Providers\OrderServiceProvider;
use MicroweberPackages\Page\PageServiceProvider;
use MicroweberPackages\Post\PostServiceProvider;
use MicroweberPackages\Product\ProductServiceProvider;
use MicroweberPackages\Role\RoleServiceProvider;
use MicroweberPackages\Shop\ShopManagerServiceProvider;
use MicroweberPackages\Tax\TaxManagerServiceProvider;

use MicroweberPackages\Tag\TagsManagerServiceProvider;
use MicroweberPackages\Template\TemplateManagerServiceProvider;
use MicroweberPackages\Utils\Captcha\Providers\CaptchaEventServiceProvider;
use MicroweberPackages\Utils\Captcha\Providers\CaptchaServiceProvider;
use MicroweberPackages\Utils\Http\Http;
use MicroweberPackages\Utils\System\ClassLoader;
use Spatie\Permission\PermissionServiceProvider;
use MicroweberPackages\App\Http\Middleware\AuthenticateSessionForUser;

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

        \Illuminate\Session\SessionServiceProvider::class,
        //  \Illuminate\Filesystem\FilesystemServiceProvider::class,
        \Illuminate\Auth\AuthServiceProvider::class,
        \Illuminate\Broadcasting\BroadcastServiceProvider::class,
        \Illuminate\Bus\BusServiceProvider::class,
        \Illuminate\Cache\CacheServiceProvider::class,
        \Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        \Illuminate\Cookie\CookieServiceProvider::class,
        \Illuminate\Database\DatabaseServiceProvider::class,
        \Illuminate\Encryption\EncryptionServiceProvider::class,
        \Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        \Illuminate\Hashing\HashServiceProvider::class,
        \Illuminate\Mail\MailServiceProvider::class,
        \Illuminate\Notifications\NotificationServiceProvider::class,
        \MicroweberPackages\Pagination\PaginationServiceProvider::class,
        \Illuminate\Pipeline\PipelineServiceProvider::class,
        \Illuminate\Queue\QueueServiceProvider::class,
        \Illuminate\Redis\RedisServiceProvider::class,
        \Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        \Illuminate\Validation\ValidationServiceProvider::class,
        \Illuminate\View\ViewServiceProvider::class,

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
        'App' => \Illuminate\Support\Facades\App::class,
        'Arr' => \Illuminate\Support\Arr::class,
        'Artisan' => \Illuminate\Support\Facades\Artisan::class,
        'Auth' => \Illuminate\Support\Facades\Auth::class,
        'Blade' => \Illuminate\Support\Facades\Blade::class,
        'Broadcast' => \Illuminate\Support\Facades\Broadcast::class,
        'Bus' => \Illuminate\Support\Facades\Bus::class,
        'Cache' => \Illuminate\Support\Facades\Cache::class,
        'Config' => \Illuminate\Support\Facades\Config::class,
        'Cookie' => \Illuminate\Support\Facades\Cookie::class,
        'Crypt' => \Illuminate\Support\Facades\Crypt::class,
        'DB' => \Illuminate\Support\Facades\DB::class,
        'Eloquent' => \Illuminate\Database\Eloquent\Model::class,
        'Event' => \Illuminate\Support\Facades\Event::class,
        //  'File' => \Illuminate\Support\Facades\File::class,
        'Gate' => \Illuminate\Support\Facades\Gate::class,
        'Hash' => \Illuminate\Support\Facades\Hash::class,
        'Http' => \Illuminate\Support\Facades\Http::class,
        'Lang' => \Illuminate\Support\Facades\Lang::class,
        'Log' => \Illuminate\Support\Facades\Log::class,
        'Mail' => \Illuminate\Support\Facades\Mail::class,
        'Notification' => \Illuminate\Support\Facades\Notification::class,
        'Password' => \Illuminate\Support\Facades\Password::class,
        'Queue' => \Illuminate\Support\Facades\Queue::class,
        'Redirect' => \Illuminate\Support\Facades\Redirect::class,
        'Redis' => \Illuminate\Support\Facades\Redis::class,
        'Request' => \Illuminate\Support\Facades\Request::class,
        'Response' => \Illuminate\Support\Facades\Response::class,
        'Route' => \Illuminate\Support\Facades\Route::class,
        'Schema' => \Illuminate\Support\Facades\Schema::class,
          'Session' => \Illuminate\Support\Facades\Session::class,
        'Storage' => \Illuminate\Support\Facades\Storage::class,
        'Str' => \Illuminate\Support\Str::class,
        'URL' => \Illuminate\Support\Facades\URL::class,
        'Validator' => \Illuminate\Support\Facades\Validator::class,
        'View' => \Illuminate\Support\Facades\View::class,
        'PDF' => \Barryvdh\DomPDF\Facade::class
    ];

    public function __construct($app)
    {
        //$this->loadPackagesComposerJson();

        ClassLoader::addDirectories([
            base_path() . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR . 'modules',
            __DIR__,
        ]);

        ClassLoader::register();

        spl_autoload_register([$this, 'autoloadModules']);

        $this->aliasInstance = AliasLoader::getInstance();

        parent::__construct($app);
    }

    public function register()
    {

        $this->registerLaravelProviders();
        $this->registerLaravelAliases();



        if ($this->app->runningInConsole()) {
            $this->commands([
                ServeTestCommand::class,
            ]);
        }

       // $this->app->register(CoreServiceProvider::class);

        $this->setEnvironmentDetection();
        $this->registerUtils();

        $this->registerSingletonProviders();

        $this->registerHtmlCollective();
        $this->registerMarkdown();

        $this->app->instance('config', new ConfigSave($this->app));
        $this->app->register(ConfigSaveServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(InstallServiceProvider::class);

        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(RepositoryEventServiceProvider::class);
        $this->app->register(MediaManagerServiceProvider::class);
        //$this->app->register(DebugbarServiceProvider::class);
        $this->app->register(ModuleServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);

        if (!defined('ADMIN_PREFIX')) {
            define('ADMIN_PREFIX', config('microweber.admin_url', 'admin'));
        }

        if (config::get('microweber.force_https') && !is_cli()) {
            URL::forceScheme("https");
        }
            //   $this->app->register(TaggableFileCacheServiceProvider::class);
        //$this->app->register(AlternativeCacheStoresServiceProvider::class);

       // $this->app->register(AssetsServiceProvider::class);
        $this->app->register(TranslationServiceProvider::class);
        $this->app->register(TagsManagerServiceProvider::class);
        $this->app->register('Conner\Tagging\Providers\TaggingServiceProvider');
        $this->app->register(EventManagerServiceProvider::class);
        $this->app->register(HelpersServiceProvider::class);
        $this->app->register(PageServiceProvider::class);
        $this->app->register(ContentServiceProvider::class);
        $this->app->register(ContentManagerServiceProvider::class);
        $this->app->register(CategoryServiceProvider::class);
        $this->app->register(CategoryEventServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(MenuEventServiceProvider::class);
        $this->app->register(ProductServiceProvider::class);
        $this->app->register(PostServiceProvider::class);
        $this->app->register(ContentDataServiceProvider::class);
        $this->app->register(ContentDataEventServiceProvider::class);
        $this->app->register(CustomFieldServiceProvider::class);
        $this->app->register(CustomFieldEventServiceProvider::class);
        $this->app->register(TemplateManagerServiceProvider::class);
        $this->app->register(DatabaseManagerServiceProvider::class);

        // Shop
        $this->app->register(ShopManagerServiceProvider::class);
        $this->app->register(TaxManagerServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
        $this->app->register(OrderEventServiceProvider::class);
        $this->app->register(CurrencyServiceProvider::class);
        $this->app->register(CheckoutManagerServiceProvider::class);
        $this->app->register(CartManagerServiceProvider::class);
        $this->app->register(ShippingManagerServiceProvider::class);
        $this->app->register(OfferServiceProvider::class);
        $this->app->register(FileManagerServiceProvider::class);
        $this->app->register(FormServiceProvider::class);
        $this->app->register(UserEventServiceProvider::class);
        $this->app->register(CartEventServiceProvider::class);
        $this->app->register(CaptchaServiceProvider::class);
        $this->app->register(CaptchaEventServiceProvider::class);
        $this->app->register(OptionServiceProvider::class);
        $this->app->register(BackupServiceProvider::class);
      //  $this->app->register(ImportServiceProvider::class);
        $this->app->register(CustomerServiceProvider::class);
        $this->app->register(CustomerEventServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
        //$this->app->register(PaymentServiceProvider::class);
        $this->app->register(RoleServiceProvider::class);
        $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        //   $this->app->register(  \L5Swagger\L5SwaggerServiceProvider::class);
        $this->app->register(SwaggerServiceProvider::class);
        //   $this->app->register(  \Laravel\Sanctum\SanctumServiceProvider::class);
        $this->app->register(CountryServiceProvider::class);
        $this->app->register(\EloquentFilter\ServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(QueueServiceProvider::class);
        $this->app->register(QueueEventServiceProvider::class);
        $this->app->register(AdminServiceProvider::class);
        $this->app->register(ContentFilterServiceProvider::class);
        $this->app->register(CommentServiceProvider::class);

        $this->aliasInstance->alias('Carbon', 'Carbon\Carbon');
        $this->app->register(CommentServiceProvider::class);
        $this->app->register(MultilanguageServiceProvider::class);


        $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        if (config('debugbar.enabled')) {
            \Barryvdh\Debugbar\Facades\Debugbar::enable();
        } else {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        if (is_cli()) {
            $this->app->register(DuskServiceProvider::class);
        }

    }

    protected function registerLaravelProviders()
    {
        $this->app->singleton('mw_migrator', function ($app) {
            $repository = $app['migration.repository'];
            return new MicroweberMigrator($repository, $app['db'], $app['files'], $app['events']);
        });

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

        if($this->app->runningUnitTests()) {
            $this->app->detectEnvironment(function () {
                return 'testing';
            });
        }


        if(isset($_SERVER['PHP_SELF']) and $_SERVER['PHP_SELF'] == 'vendor/phpunit/phpunit/phpunit') {
            $this->app->detectEnvironment(function () {
                return 'testing';
            });
        }

        if(isset($_SERVER['PHP_SELF']) and $_SERVER['PHP_SELF'] == 'artisan') {
            if(isset($_SERVER['argv'][1]) and $_SERVER['argv'][1] == 'dusk') {
                $this->app->detectEnvironment(function () {
                    return 'testing';
                });
            } else {

            $this->app->detectEnvironment(function () {
                return app()->environment();
            });
            }
        }


        if (defined('MW_UNIT_TEST')) {
            $this->app->detectEnvironment(function () {
                if (!defined('MW_UNIT_TEST_ENV_FROM_TEST')) {
                    return 'testing';
                }

                return MW_UNIT_TEST_ENV_FROM_TEST;
            });
        }


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

                if(!$domain){
                    return app()->environment();
                }

                $port = explode(':', $domain);

                $domain = str_ireplace('www.', '', $domain);

                if (isset($port[1])) {
                    $domain = str_ireplace(':' . $port[1], '', $domain);
                }

                if(is_dir(config_path($domain)) and is_file(config_path($domain) . '/microweber.php')) {
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
            'log_manager' => 'LogManager',
            'permalink_manager' => 'PermalinkManager',
            'layouts_manager' => 'LayoutsManager',
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
        $this->app->register('Collective\Html\HtmlServiceProvider');

        $this->aliasInstance->alias('Form', 'Collective\Html\FormFacade');
        $this->aliasInstance->alias('HTML', 'Collective\Html\HtmlFacade');
    }

    protected function registerMarkdown()
    {
        $this->app->register('GrahamCampbell\Markdown\MarkdownServiceProvider');

        $this->aliasInstance->alias('Markdown', 'GrahamCampbell\Markdown\Facades\Markdown');
    }

    public function boot(\Illuminate\Routing\Router $router)
    {
        View::addNamespace('app', __DIR__ . '/../resources/views');

        \App::instance('path.public', base_path());

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
            }

            load_all_functions_files_for_modules($this->app);

            $this->setupAppLocale();

            if (is_cli()) {

                $this->commands('MicroweberPackages\Option\Console\Commands\OptionCommand');
                $this->commands('MicroweberPackages\Console\Commands\UpdateCommand');
                $this->commands('MicroweberPackages\Console\Commands\ModuleCommand');
                $this->commands('MicroweberPackages\Console\Commands\ReloadDatabaseCommand');
                $this->commands('MicroweberPackages\Console\Commands\PackageInstallCommand');

            }
        } else {
            // Otherwise register the install command
            $this->commands('MicroweberPackages\Install\Console\Commands\InstallCommand');
        }


        if (class_exists(\App\Providers\AppServiceProvider::class)) {
            app()->register(\App\Providers\AppServiceProvider::class);
        }

         $this->loadRoutesFrom(dirname(__DIR__) . '/routes/web.php');

        if (mw_is_installed()) {
             $this->app->event_manager->trigger('mw.after.boot', $this);
        }

        // >>> MW Kernel add
      //  $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware( \MicroweberPackages\App\Http\Middleware\TrustProxies::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Fruitcake\Cors\HandleCors::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\MicroweberPackages\App\Http\Middleware\CheckForMaintenanceMode::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\MicroweberPackages\App\Http\Middleware\TrimStrings::class);
       // $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Session\Middleware\StartSession::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\MicroweberPackages\App\Http\Middleware\StartSessionExtended::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class);
        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);

        $router->pushMiddlewareToGroup('web', \MicroweberPackages\App\Http\Middleware\EncryptCookies::class);
        $router->pushMiddlewareToGroup('web', AuthenticateSessionForUser::class);
        $router->pushMiddlewareToGroup('web',  \Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $router->pushMiddlewareToGroup('web',  \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class);
        $router->pushMiddlewareToGroup('web',  \Illuminate\Routing\Middleware\SubstituteBindings::class);

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
        $router->aliasMiddleware('admin', \MicroweberPackages\App\Http\Middleware\Admin::class);
        $router->aliasMiddleware('api_auth', \MicroweberPackages\App\Http\Middleware\ApiAuth::class);
        $router->aliasMiddleware('allowed_ips', \MicroweberPackages\App\Http\Middleware\AllowedIps::class);

        $router->middlewareGroup('public.web',[
            'xss',
            MultilanguageMiddleware::class,
            AuthenticateSessionForUser::class,
        ]);

        $router->middlewareGroup('api',[
            'xss',
           // 'throttle:1000,1',
            'api_auth'
        ]);
        $router->middlewareGroup('public.api',[
            'xss',
         //   'throttle:1000,1'
        ]);
        $router->middlewareGroup('static.api',[
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



    /**
     * @deprecated
     */
    public function loadPackagesComposerJson()
    {
        $dir = dirname(dirname(__DIR__));

        $cached = base_path('bootstrap/cache/microweber_packages.json');
        if (is_file($cached) and (time() - filemtime($cached) >= 15 * 60)) { // 30 minutes ago
            $cache_content = json_decode(file_get_contents($cached), TRUE);
            if (is_array($cache_content) && !empty($cache_content)) {
                foreach ($cache_content as $file) {
                    $file_path_inlude = $dir . $file;
                    if (is_file($file_path_inlude)) {
                        require_once($file_path_inlude);
                    }
                }
                return;
            }
        }

        $files_map = [];
        $packages = glob("$dir/*/composer.json");

        //find a php file in each folder and get its realpath
        foreach ($packages as $filename) {

            $phpfile = realpath($filename);
            $cont = @json_decode(@file_get_contents($phpfile), 1);

            if (isset($cont['autoload'])) {
                if (isset($cont['autoload']['files']) and is_array($cont['autoload']['files']) and !empty($cont['autoload']['files'])) {
                    foreach ($cont['autoload']['files'] as $file) {
                        $package_dir = dirname($phpfile) . DS;
                        $file_path = $package_dir . $file;
                        if (is_file($file_path)) {
                            $file_path_save = $file_path;
                            $file_path_save = str_replace($dir, '', $file_path_save);
                            $files_map[] = $file_path_save;

                            require_once($file_path);
                        }
                    }
                }
            }

        }

        if (!empty($files_map)) {
            file_put_contents($cached, json_encode($files_map));
        }
    }

    private function setupAppLocale()
    {
        $isLocaleChangedFromMultilanguageLogics = false;

        $currentUri = request()->path();

        //  Change language if user request language with LINK has lang abr
        if (MultilanguageHelpers::multilanguageIsEnabled()) {

            $localeIsChangedFromGetRequest = false;
            $locale = request()->get('locale');
            if (is_lang_correct($locale)) {
                $localeIsChangedFromGetRequest = true;
                $isLocaleChangedFromMultilanguageLogics = true;
                $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($locale);
                if (!empty($localeSettings) && isset($localeSettings['is_active']) && $localeSettings['is_active'] =='y') {
                    change_language_by_locale($locale, true);
                }
            }

            // if locale is not changed from get request we must to chek URL SLUGS
            if (!$localeIsChangedFromGetRequest) {
                $linkSegments = url_segment(-1, $currentUri);
                $linkSegments = array_filter($linkSegments, 'trim');

                if (!empty($linkSegments)) {
                    if (isset($linkSegments[0]) and $linkSegments[0]) {
                        $localeSettings = app()->multilanguage_repository->getSupportedLocaleByDisplayLocale($linkSegments[0]);
                        if (!$localeSettings) {
                            $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($linkSegments[0]);
                        }
                        if ($localeSettings and isset($localeSettings['locale']) && isset($localeSettings['is_active']) && $localeSettings['is_active'] =='y') {
                            $isLocaleChangedFromMultilanguageLogics = true;
                            change_language_by_locale($localeSettings['locale'], true);
                        }
                    }
                }
            }
        }

        // If locale is not changed from link
        if (!$isLocaleChangedFromMultilanguageLogics) {
            // If we have a lang cookie read from theere
            if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang'])) {
                $setCurrentLangTo = $_COOKIE['lang'];
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
                $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($setCurrentLangTo);
                if (!empty($localeSettings) && isset($localeSettings['is_active']) && $localeSettings['is_active'] =='y') {
                    set_current_lang($setCurrentLangTo);
                }
            }
        }
    }
}
