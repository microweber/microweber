<?php

namespace MicroweberPackages\Core\tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Config;

class ConfigFileTest extends TestCase
{
    #[Test]
    public function it_something_is_true(): void {
        $this->assertTrue(true);
    }

    #[Test]

    public function it_config_read(): void {
        $connection = Config::get('database.connections');
        $this->assertTrue(!empty($connection));
    }

    #[Test]
    public function it_config_write(): void {
        $now = date('Y-m-d H:i:s');
        $old = Config::get('Microweber_tests.last_test');

        Config::set('Microweber_tests.last_test', $now);
        $current = Config::get('Microweber_tests.last_test');

        Config::save(['Microweber_tests']);

        $get = Config::get('Microweber_tests.last_test');

        $this->assertTrue(!empty($get));
        $this->assertTrue($now == $get);
        $this->assertTrue($current == $get);
        $this->assertTrue($old != $get);

        // Clean up saved file
        $configFile = config_path('Microweber_tests.php');
        if (is_file($configFile)) {
            @unlink($configFile);
        }
        // Also clean env dir
        $envFile = config_path(app()->environment() . '/Microweber_tests.php');
        if (is_file($envFile)) {
            @unlink($envFile);
        }
    }
    #[Test]
    public function it_version_txt_new_line(): void {
        $version_txt = file_get_contents(MW_ROOTPATH . '/version.txt');
        $this->assertEquals($version_txt, trim($version_txt), 'version.txt file should not have new line at the end');
    }
}
