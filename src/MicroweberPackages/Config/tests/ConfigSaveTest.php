<?php
namespace MicroweberPackages\Config\tests;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Config\ConfigSave;
use MicroweberPackages\Core\tests\TestCase;

class ConfigSaveTest extends TestCase
{
    public function testSimple()
    {
        Config::set('microweber.firstName', 'Bozhidar');
        Config::set('microweber.lastName', 'Slaveykov');

        Config::save(['microweber']);

        $this->assertEquals('Bozhidar', Config::get('microweber.firstName'));
        $this->assertEquals('Slaveykov', Config::get('microweber.lastName'));

        $defaultDir = $this->app->configPath();
        $configFile = $defaultDir . DIRECTORY_SEPARATOR . $this->app->environment(). DIRECTORY_SEPARATOR . 'microweber.php';

        $this->assertTrue(is_file($configFile));

        $configFileContent = include($configFile);

        $this->assertNotEmpty($configFileContent);

        $this->assertEquals('Bozhidar', $configFileContent['firstName']);
        $this->assertEquals('Slaveykov', $configFileContent['lastName']);

    }

}