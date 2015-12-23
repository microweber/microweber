<?php namespace Microweber;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ClassLoader;
use Illuminate\Foundation\AliasLoader;

use Illuminate\Http\Request;
use Illuminate\Config\FileLoader;
use \Cache;
use \App;
use \Microweber\Utils\Adapters\Config\ConfigSave as ConfigSave;

if (!defined('MW_VERSION')){
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'bootstrap.php');
}

class MicroweberServiceProvider extends ServiceProvider {

    public function __construct($app) {
        ClassLoader::addDirectories(array(
            base_path() . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR . 'modules',
            __DIR__,
        ));

        ClassLoader::register();
        spl_autoload_register(array($this, 'autoloadModules'));

        parent::__construct($app);
    }

    public function register() {


        // Set environment

        if (!$this->app->runningInConsole()){
            $domain = $_SERVER['HTTP_HOST'];
            $this->app->detectEnvironment(function () use ($domain) {
                if (getenv('APP_ENV')){
                    return getenv('APP_ENV');
                }

                $domain = str_ireplace('www.', '', $domain);
                $domain = str_ireplace(':' . $_SERVER['SERVER_PORT'], '', $domain);

                return $domain;
            });
        }

        $this->app->instance('config', new ConfigSave($this->app));

        $this->app->singleton(
            'Illuminate\Cache\StoreInterface',
            'Utils\Adapters\Cache\CacheStore'
        );


        $this->app->singleton('event_manager', function ($app) {
            return new Providers\Event($app);
        });
        $this->app->singleton('database_manager', function ($app) {
            return new Providers\DatabaseManager($app);
        });
        $this->app->singleton('format', function ($app) {
            return new Utils\Format($app);
        });
        $this->app->singleton('parser', function ($app) {
            return new Utils\Parser($app);
        });

        $this->app->bind('http', function ($app) {
            return new Utils\Http($app);
        });

        $this->app->bind('captcha', function ($app) {
            return new Utils\Captcha($app);
        });

        $this->app->singleton('url_manager', function ($app) {
            return new Providers\UrlManager($app);
        });
        $this->app->singleton('ui', function ($app) {
            return new Providers\Ui($app);
        });
        $this->app->singleton('content_manager', function ($app) {
            return new Providers\ContentManager($app);
        });
        $this->app->singleton('update', function ($app) {
            return new Providers\UpdateManager($app);
        });
        $this->app->singleton('cache_manager', function ($app) {
            return new Providers\CacheManager($app);
        });
        $this->app->singleton('config_manager', function ($app) {
            return new Providers\ConfigurationManager($app);
        });
        $this->app->singleton('media_manager', function ($app) {
            return new Providers\MediaManager($app);
        });
        $this->app->singleton('fields_manager', function ($app) {
            return new Providers\FieldsManager($app);
        });

        $this->app->singleton('data_fields_manager', function ($app) {
            return new Providers\Content\DataFieldsManager($app);
        });

        $this->app->singleton('attributes_manager', function ($app) {
            return new Providers\Content\AttributesManager($app);
        });
        $this->app->singleton('forms_manager', function ($app) {
            return new Providers\FormsManager($app);
        });

        $this->app->singleton('email_notifications_manager', function ($app) {
            return new Providers\EmailNotificationsManager($app);
        });
        $this->app->singleton('notifications_manager', function ($app) {
            return new Providers\NotificationsManager($app);
        });
        $this->app->singleton('log_manager', function ($app) {
            return new Providers\LogManager($app);
        });
        $this->app->singleton('option_manager', function ($app) {
            return new Providers\OptionManager($app);
        });


        $this->app->singleton('template', function ($app) {
            return new Providers\Template($app);
        });
        $this->app->singleton('modules', function ($app) {
            return new Providers\Modules($app);
        });
        $this->app->singleton('category_manager', function ($app) {
            return new Providers\CategoryManager($app);
        });

        $this->app->singleton('menu_manager', function ($app) {
            return new Providers\MenuManager($app);
        });
        $this->app->singleton('user_manager', function ($app) {
            return new Providers\UserManager($app);
        });


        // Shop

        $this->app->singleton('shop_manager', function ($app) {
            return new Providers\ShopManager($app);
        });

        $this->app->singleton('cart_manager', function ($app) {
            return new Providers\Shop\CartManager($app);
        });

        $this->app->singleton('order_manager', function ($app) {
            return new Providers\Shop\OrderManager($app);
        });

        $this->app->singleton('tax_manager', function ($app) {
            return new Providers\Shop\TaxManager($app);
        });

        $this->app->singleton('checkout_manager', function ($app) {
            return new Providers\Shop\CheckoutManager($app);
        });

        // Other

        $this->app->singleton('layouts_manager', function ($app) {
            return new Providers\LayoutsManager($app);
        });

        $this->app->singleton('template_manager', function ($app) {
            return new Providers\TemplateManager($app);
        });
        $this->app->singleton('ui', function ($app) {
            return new Providers\Ui($app);
        });


        $this->app->register('Illuminate\Html\HtmlServiceProvider');
        AliasLoader::getInstance()->alias("Form", 'Illuminate\Html\FormFacade');
        AliasLoader::getInstance()->alias("HTML", 'Illuminate\Html\HtmlFacade');


        $this->app->register('GrahamCampbell\Markdown\MarkdownServiceProvider');
        AliasLoader::getInstance()->alias("Markdown", 'GrahamCampbell\Markdown\Facades\Markdown');


        // $this->app->register('SocialiteProviders\Manager\ServiceProvider');


    }

    public function boot(Request $request) {
        parent::boot();

        // public = /
        App::instance('path.public', base_path());

        Cache::extend('file', function ($app) {
            return new Utils\Adapters\Cache\CacheStore;
        });

        // If installed load module functions and set locale
        if (mw_is_installed()){
            $modules = load_all_functions_files_for_modules();
            $this->commands('Microweber\Commands\OptionCommand');

            $language = get_option('language', 'website');
            if ($language!=false){
                set_current_lang($language);
            }
            if ($this->app->runningInConsole()){
                $this->commands('Microweber\Commands\UpdateCommand');
            }
        } else {
            // Otherwise register the install command
            $this->commands('Microweber\Commands\InstallCommand');

        }


        // Register routes
        $this->registerRoutes();
    }

    private function registerRoutes() {
        $routesFile = __DIR__ . '/routes.php';
        if (file_exists($routesFile)){
            include $routesFile;

            return true;
        }

        return false;
    }

    function autoloadModules($className) {
        $filename = modules_path() . $className . ".php";
        $filename = normalize_path($filename, false);

        if (!class_exists($className, false)){
            if (is_file($filename)){
                require_once $filename;
            }
        }
    }


}