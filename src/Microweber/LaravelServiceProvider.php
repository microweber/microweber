<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/22/14
 * Time: 3:09 PM
 */

namespace Microweber;

error_reporting(E_ALL);
ini_set('display_errors', 1);
//use Illuminate\Support\Facades\Config;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ClassLoader;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Http\Request;
use Illuminate\Config\FileLoader;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\AliasLoader;

include_once(__DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'bootstrap.php');

class LaravelServiceProvider extends ServiceProvider
{


    public function __construct($app)
    {

        parent::__construct($app);

        ClassLoader::addDirectories(array(
            base_path() . '/userfiles/modules',
        ));
        ClassLoader::register();

    }

    public function register()
    {

//        $this->app->bindIf('cssssonfig.loader', function($app){
//
//            return new Utils\FileLoader(new Filesystem, $app['path'].'/config');
//
//         }, true);

        $this->app->bind('config', function ($app) {
            return new Providers\SaveConfig($app->getConfigLoader(), $app->environment());
        });

        $this->app->bind('database', function ($app) {
            return new Providers\Database($app);
        });

        $this->app->singleton('format', function ($app) {
            return new Utils\Format($app);
        });

        $this->app->extend('url', function ($app) {
            return new Utils\Url($app);
        });



        $this->app->singleton('cache_manager', function ($app) {
            return new Providers\CacheManager($app);
        });
        $this->app->bind('template', function ($app) {
            return new Providers\Template($app);
        });
        $this->app->bind('modules', function ($app) {
            return new Providers\Modules($app);
        });





//        $this->app->extend('db', function ($app) {
//            return new Db($app);
//        });

        $this->app->bind('user', function ($app) {
            return new Providers\User($app);
        });
        Event::listen('illuminate.query', function($sql, $bindings, $time){
            echo $sql;          // select * from my_table where id=?
            print_r($bindings); // Array ( [0] => 4 )
            echo $time;         // 0.58

            // To get the full sql query with bindings inserted
            $sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
            $full_sql = vsprintf($sql, $bindings);
        });

    }

//    protected function registerCache()
//    {
//        $this->app['mw.cache'] = $this->app->share(function ($app) {
//            return new Models\Cache($app);
//        });
//    }
//
//
//    protected function registerHtmlBuilder()
//    {
//        $this->app->bind('config', function($app)
//        {
//            return new SaveConfig($app->getConfigLoader(), $app->environment());
//        });
//    }
//
//    protected function registerFormBuilder()
//    {
//        $this->app['form'] = $this->app->share(function ($app) {
//            return new \admin\Controller();
//        });
//    }


} 