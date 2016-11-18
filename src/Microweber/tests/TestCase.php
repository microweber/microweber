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


        $config_folder = __DIR__ . '/../../../config/testing/';
        $mw_file = $config_folder . 'microweber.php';

        if (!is_dir($config_folder)) {
            mkdir($config_folder);
        }

        $unitTesting = true;
        $testEnvironment = 'testing';

        $app = require __DIR__ . '/../../../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        $app['env'] = 'testing';
        $this->assertEquals(true, is_dir($config_folder));

        $app->detectEnvironment(function () {
            return 'testing';
        });

        $environment = $app->environment();


        $this->sqlite_file = storage_path() . '/phpunit.sqlite';


        file_put_contents($mw_file, "<?php return array (
            'is_installed' => 0,
            'install_default_template' => 'default',
            'install_default_template_content' => 1,
            );"
        );

        if (is_file($this->sqlite_file)) {
            @unlink($this->sqlite_file);
        }

        // make fresh install
        $install_params = array(

            'username' => 'test',
            'password' => 'test',
            'email' => 'test@example.com',
            'db_driver' => 'sqlite',
            'db_host' => '',
            'db_user' => '',
            'db_pass' => '',
            'db_name' => $this->sqlite_file,
            '--env' => 'testing',
        );
        $is_installed = mw_is_installed();
        if (!$is_installed) {
            $install = \Artisan::call('microweber:install', $install_params);
            $this->assertEquals(0, $install);
        }


        return $app;


        return $app;
    }

    public function setUp()
    {


        parent::setUp();


    }


}

