<?php

namespace MicroweberPackages\LaravelConfigExtended\Tests;

use Illuminate\Support\Facades\Config;


class ConfigSaveTest extends \MicroweberPackages\LaravelConfigExtended\Tests\TestCase
{

    public function testSimple()
    {

        Config::set('microweber.firstName', 'Bozhidar');
        Config::set('microweber.lastName', 'Slaveykov');

        $time = time();
        Config::set('microweber.time', $time);
        Config::save(['microweber']);

        $this->assertEquals('Bozhidar', Config::get('microweber.firstName'));
        $this->assertEquals('Slaveykov', Config::get('microweber.lastName'));

        $defaultDir = $this->app->configPath();
        //remove the env dir

        $configFileEnvDir = $defaultDir . DIRECTORY_SEPARATOR . $this->app->environment();
        if (is_dir($configFileEnvDir)) {
            $this->assertTrue(rmdir_recursive($configFileEnvDir));
        }

        $configFile = $defaultDir . DIRECTORY_SEPARATOR . 'microweber.php';

        if(!is_file($configFile)){
           unlink($configFile);
        }

        $this->assertTrue(is_file($configFile));

        $configFileContent = include($configFile);

        $this->assertNotEmpty($configFileContent);

        $this->assertEquals('Bozhidar', $configFileContent['firstName']);
        $this->assertEquals('Slaveykov', $configFileContent['lastName']);
        $this->assertEquals($time, $configFileContent['time']);

    }

    public function testWithConfigFromEnvDirectorty()
    {
        $defaultDir = $this->app->configPath();

        $configFileEnvDir = $defaultDir . DIRECTORY_SEPARATOR . $this->app->environment();

        if (!is_dir($configFileEnvDir)) {
            mkdir_recursive($configFileEnvDir);
        }

        Config::set('microweber.firstName', 'Bozhidar1');
        Config::set('microweber.lastName', 'Slaveykov2');
        Config::set('microweber.storageTest', storage_path() . DIRECTORY_SEPARATOR . 'test');
        Config::set('microweber.storageTest', storage_path() . DIRECTORY_SEPARATOR . 'test');
        $time = time();
        Config::set('microweber.time', $time);
        Config::save(['microweber']);

        $this->assertEquals('Bozhidar1', Config::get('microweber.firstName'));
        $this->assertEquals('Slaveykov2', Config::get('microweber.lastName'));
        $this->assertEquals($time, Config::get('microweber.time'));

        rmdir_recursive($configFileEnvDir);
    }

}