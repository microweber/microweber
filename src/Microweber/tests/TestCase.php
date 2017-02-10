<?php

namespace Microweber\tests;

use Illuminate\Support\Facades\DB;


class TestCase extends \Illuminate\Foundation\Testing\TestCase
{

    private $sqlite_file = 'phpunit.sqlite';



    public function createApplication()
    {

        if (!defined('MW_UNIT_TEST')) {
            define('MW_UNIT_TEST', true);
        }
        $testing_env_name = 'testing';

        $config_folder = __DIR__ . '/../../../config/testing/';
        $mw_file = $config_folder . 'microweber.php';

        if (!is_dir($config_folder)) {
            mkdir($config_folder);
        }

        $unitTesting = true;
        $testEnvironment = env('APP_ENV') ? env('APP_ENV') : 'testing';
        $test_env_from_conf = env('APP_ENV_TEST_FROM_CONFIG');
        if ($test_env_from_conf) {
            $testing_env_name = $testEnvironment = $test_env_from_conf;
            putenv("APP_ENV=$testing_env_name");
            if (!defined('MW_UNIT_TEST_ENV_FROM_TEST')) {
                define('MW_UNIT_TEST_ENV_FROM_TEST', $testing_env_name);
                $config_folder = __DIR__ . '/../../../config/'.$testing_env_name.'/';
                $config_folder = realpath($config_folder);
                $mw_file = $config_folder . '/microweber.php';
            }
        }
        file_put_contents($mw_file, "<?php return array (
            'is_installed' => 0,
            'compile_assets' => 0,
            'install_default_template' => 'default',
            'install_default_template_content' => 1,
            );"
        );

        $app = require __DIR__ . '/../../../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        $app['env'] = $testing_env_name;

        $this->assertEquals(true, is_dir($config_folder));

        $app->detectEnvironment(function () use ($testing_env_name) {
            return $testing_env_name;
        });

        $environment = $app->environment();

        $this->sqlite_file = storage_path() . '/phpunit.' . $environment . '.sqlite';



        if (is_file($this->sqlite_file)) {
            // @unlink($this->sqlite_file);
        }

        $db_driver = 'sqlite';
        $db_host = '';
        $db_user = '';
        $db_pass = '';
        $db_prefix = '';
        $db_name = $this->sqlite_file;
        if ($test_env_from_conf) {
            $dbEngines = \Config::get('database.connections');
            $defaultDbEngine = \Config::get('database.default');
            $default = \Config::get('database');

            if ($defaultDbEngine) {
                $db_driver = $defaultDbEngine;
                if (isset($dbEngines[$defaultDbEngine]) and is_array($dbEngines[$defaultDbEngine])) {
                    $config = $dbEngines[$defaultDbEngine];
                    if (isset($config['database'])) {
                        $db_name = $config['database'];
                    }
                    if (isset($config['host'])) {
                        $db_host = $config['host'];
                    }
                    if (isset($config['host'])) {
                        $db_host = $config['host'];
                    }
                    if (isset($config['username'])) {
                        $db_user = $config['username'];
                    }
                    if (isset($config['password'])) {
                        $db_pass = $config['password'];
                    }
                    if (isset($config['prefix'])) {
                        $db_prefix = $config['prefix'];
                    }
                }
            }

        }

        // make fresh install
        $install_params = array(

            'username' => 'test',
            'password' => 'test',
            'email' => 'test@example.com',
            'db_driver' => $db_driver,
            'db_host' => $db_host,
            'db_user' => $db_user,
            'db_pass' => $db_pass,
            'db_name' => $db_name,
            'prefix' => $db_prefix,
            // 'db_name' => ':memory:',
            '--env' => $environment,
        );

        $is_installed = mw_is_installed();

        if (!$is_installed) {
            $install = \Artisan::call('microweber:install', $install_params);
            $this->assertEquals(0, $install);
        }

        return $app;
    }

    public static function setUpBeforeClass()
    {

        $test_env_from_conf = env('APP_ENV_TEST_FROM_CONFIG');
        if ($test_env_from_conf) {
            $testing_env_name = $testEnvironment = $test_env_from_conf;
            putenv("APP_ENV=$testing_env_name");
        }
        parent::setUpBeforeClass();

    }


}

