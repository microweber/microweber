<?php

namespace Microweber;

use App;
use Cache;
use Microweber\Utils\ClassLoader;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Microweber\Utils\Adapters\Config\ConfigSave;

if (! defined('MW_VERSION')) {
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'bootstrap.php';
}

class MicroweberServiceProvider extends ServiceProvider
{
    protected $aliasInstance;

    /*
    * Application Service Providers...
    */
    public $laravel_providers = [
        'Microweber\App\Providers\Illuminate\ArtisanServiceProvider',
        'Microweber\App\Providers\Illuminate\AuthServiceProvider',
        'Microweber\App\Providers\Illuminate\CacheServiceProvider',
        'Microweber\App\Providers\Illuminate\ConsoleSupportServiceProvider',
        'Microweber\App\Providers\Illuminate\CookieServiceProvider',
        'Microweber\App\Providers\Illuminate\DatabaseServiceProvider',
        'Microweber\App\Providers\Illuminate\EncryptionServiceProvider',
        'Microweber\App\Providers\Illuminate\FilesystemServiceProvider',
        'Microweber\App\Providers\Illuminate\FoundationServiceProvider',
        'Microweber\App\Providers\Illuminate\HashServiceProvider',
        'Microweber\App\Providers\Illuminate\MailServiceProvider',
        'Microweber\App\Providers\Illuminate\PaginationServiceProvider',
        'Microweber\App\Providers\Illuminate\QueueServiceProvider',
        'Microweber\App\Providers\Illuminate\RedisServiceProvider',
        'Microweber\App\Providers\Illuminate\PasswordResetServiceProvider',
        'Microweber\App\Providers\Illuminate\SessionServiceProvider',
        'Microweber\App\Providers\Illuminate\TranslationServiceProvider',
        'Microweber\App\Providers\Illuminate\ValidationServiceProvider',
        'Microweber\App\Providers\Illuminate\ViewServiceProvider',
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
        'App' => 'Microweber\App\Providers\Illuminate\Support\Facades\App',
        'Artisan' => 'Microweber\App\Providers\Illuminate\Support\Facades\Artisan',
        'Auth' => 'Microweber\App\Providers\Illuminate\Support\Facades\Auth',
        'Blade' => 'Microweber\App\Providers\Illuminate\Support\Facades\Blade',
        'Cache' => 'Microweber\App\Providers\Illuminate\Support\Facades\Cache',
        'Config' => 'Microweber\App\Providers\Illuminate\Support\Facades\Config',
        'Cookie' => 'Microweber\App\Providers\Illuminate\Support\Facades\Cookie',
        'Crypt' => 'Microweber\App\Providers\Illuminate\Support\Facades\Crypt',
        'DB' => 'Microweber\App\Providers\Illuminate\Support\Facades\DB',
        'Event' => 'Microweber\App\Providers\Illuminate\Support\Facades\Event',
        'File' => 'Microweber\App\Providers\Illuminate\Support\Facades\File',
        'Hash' => 'Microweber\App\Providers\Illuminate\Support\Facades\Hash',
        'Input' => 'Microweber\App\Providers\Illuminate\Support\Facades\Input',
        'Lang' => 'Microweber\App\Providers\Illuminate\Support\Facades\Lang',
        'Log' => 'Microweber\App\Providers\Illuminate\Support\Facades\Log',
        'Mail' => 'Microweber\App\Providers\Illuminate\Support\Facades\Mail',
        'Paginator' => 'Microweber\App\Providers\Illuminate\Support\Facades\Paginator',
        'Password' => 'Microweber\App\Providers\Illuminate\Support\Facades\Password',
        'Queue' => 'Microweber\App\Providers\Illuminate\Support\Facades\Queue',
        'Redirect' => 'Microweber\App\Providers\Illuminate\Support\Facades\Redirect',
        'Redis' => 'Microweber\App\Providers\Illuminate\Support\Facades\Redis',
        'Request' => 'Microweber\App\Providers\Illuminate\Support\Facades\Request',
        'Response' => 'Microweber\App\Providers\Illuminate\Support\Facades\Response',
        'Route' => 'Microweber\App\Providers\Illuminate\Support\Facades\Route',
        'Schema' => 'Microweber\App\Providers\Illuminate\Support\Facades\Schema',
        'Session' => 'Microweber\App\Providers\Illuminate\Support\Facades\Session',
        'URL' => 'Microweber\App\Providers\Illuminate\Support\Facades\URL',
        'Validator' => 'Microweber\App\Providers\Illuminate\Support\Facades\Validator',
        'View' => 'Microweber\App\Providers\Illuminate\Support\Facades\View',
    ];

    public function __construct($app)
    {
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

        $this->setEnvironmentDetection();

        $this->registerUtils();

        $this->registerSingletonProviders();

        $this->registerHtmlCollective();

        $this->registerMarkdown();

        $this->app->instance('config', new ConfigSave($this->app));

        $this->app->register('Conner\Tagging\Providers\TaggingServiceProvider');

        $this->aliasInstance->alias('Carbon', 'Carbon\Carbon');
    }

    protected function registerLaravelProviders()
    {
        foreach ($this->laravel_providers as $provider) {
            $this->app->register($provider);
        }

        $this->app->bind('Illuminate\Contracts\Bus\Dispatcher', 'Illuminate\Bus\Dispatcher');

        $this->app->bind('Illuminate\Contracts\Queue\Queue', 'Illuminate\Contracts\Queue\Queue');

        $this->app->singleton(
            'Illuminate\Cache\StoreInterface',
            'Utils\Adapters\Cache\CacheStore'
        );

        $this->app->singleton(
            'Illuminate\Contracts\Console\Kernel',
            'Microweber\App\Console\Kernel'
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
        if (! is_cli()) {
            if(isset($_SERVER['HTTP_HOST'])){
            $domain = $_SERVER['HTTP_HOST'];
            } else if(isset($_SERVER['SERVER_NAME'])){
                $domain = $_SERVER['SERVER_NAME'];
            }

            return $this->app->detectEnvironment(function () use ($domain) {
                if (getenv('APP_ENV')) {
                    return getenv('APP_ENV');
                }

                $port = explode(':', $domain);

                $domain = str_ireplace('www.', '', $domain);

                if (isset($port[1])) {
                    $domain = str_ireplace(':' . $port[1], '', $domain);
                }

                return strtolower($domain);
            });
        }

        if (defined('MW_UNIT_TEST')) {
            $this->app->detectEnvironment(function () {
                if (! defined('MW_UNIT_TEST_ENV_FROM_TEST')) {
                    return 'testing';
                }

                return MW_UNIT_TEST_ENV_FROM_TEST;
            });
        }
    }

    protected function registerUtils()
    {
        $this->app->bind('http', function ($app) {
            return new Utils\Http($app);
        });

        $this->app->singleton('format', function ($app) {
            return new Utils\Format($app);
        });

        $this->app->singleton('parser', function ($app) {
            return new Utils\Parser($app);
        });
    }

    protected function registerSingletonProviders()
    {
        $providers = [
            'lang_helper' => 'Helpers\Lang',
            'event_manager' => 'Event',
            'database_manager' => 'DatabaseManager',
            'url_manager' => 'UrlManager',
            'ui' => 'Ui',
            'content_manager' => 'ContentManager',
            'update' => 'UpdateManager',
            'cache_manager' => 'CacheManager',
            'config_manager' => 'ConfigurationManager',
            'media_manager' => 'MediaManager',
            'fields_manager' => 'FieldsManager',
            'data_fields_manager' => 'Content\DataFieldsManager',
            'tags_manager' => 'Content\TagsManager',
            'attributes_manager' => 'Content\AttributesManager',
            'forms_manager' => 'FormsManager',
            'notifications_manager' => 'NotificationsManager',
            'log_manager' => 'LogManager',
            'option_manager' => 'OptionManager',
            'template' => 'Template',
            'modules' => 'Modules',
            'category_manager' => 'CategoryManager',
            'menu_manager' => 'MenuManager',
            'user_manager' => 'UserManager',
            'shop_manager' => 'ShopManager',
            'cart_manager' => 'Shop\CartManager',
            'order_manager' => 'Shop\OrderManager',
            'tax_manager' => 'Shop\TaxManager',
            'checkout_manager' => 'Shop\CheckoutManager',
            'layouts_manager' => 'LayoutsManager',
            'template_manager' => 'TemplateManager',
            'captcha_manager' => 'CaptchaManager',
        ];

        foreach ($providers as $alias => $class) {
            $this->app->singleton($alias, function ($app) use ($class) {
                $class = 'Microweber\\Providers\\' . $class;

                return new $class($app);
            });
        }
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

    public function boot()
    {
        App::instance('path.public', base_path());

        Cache::extend('file', function () {
            return new Utils\Adapters\Cache\CacheStore();
        });

        // If installed load module functions and set locale
        if (mw_is_installed()) {
            load_all_functions_files_for_modules();

            $this->commands('Microweber\Commands\OptionCommand');

            $language = get_option('language', 'website');

            if ($language != false) {
                set_current_lang($language);
            }

            if (is_cli()) {

                $this->commands('Microweber\Commands\ResetCommand');
                $this->commands('Microweber\Commands\UpdateCommand');
                $this->commands('Microweber\Commands\ModuleCommand');
                $this->commands('Microweber\Commands\PackageInstallCommand');

            }
        } else {
            // Otherwise register the install command
            $this->commands('Microweber\Commands\InstallCommand');
        }

        $this->loadRoutes();

        $this->app->event_manager->trigger('mw.after.boot', $this);
    }

    private function loadRoutes()
    {
        $routesFile = __DIR__ . '/routes.php';

        if (file_exists($routesFile)) {
            include $routesFile;
        }
    }

    public function autoloadModules($className)
    {
        $filename = modules_path() . $className . '.php';

        $filename = normalize_path($filename, false);

        if (! class_exists($className, false) && is_file($filename)) {
            require_once $filename;
        }
    }
}
