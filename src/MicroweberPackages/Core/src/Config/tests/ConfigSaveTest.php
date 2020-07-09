<?php
namespace MicroweberPackages\Config\tests;

class ConfigSaveTest extends BaseTest
{
    public function testSimple()
    {
        Config::set('microweber.firstName', 'Bozhidar');
        Config::set('microweber.lastName', 'Slaveykov');

        Config::save(['microweber']);

        $this->assertEquals('Bozhidar', Config::get('microweber.firstName'));
        $this->assertEquals('Slaveykov', Config::get('microweber.lastName'));

        $configFile = config_path('microweber.php');

        $this->assertTrue(is_file($configFile));

        $configFileContent = include($configFile);

        $this->assertNotEmpty($configFileContent);

        $this->assertEquals('Bozhidar', $configFileContent['firstName']);
        $this->assertEquals('Slaveykov', $configFileContent['lastName']);

    }

}