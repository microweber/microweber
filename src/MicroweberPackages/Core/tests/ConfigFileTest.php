<?php

namespace MicroweberPackages\Core\tests;

use Illuminate\Support\Facades\Config;

class ConfigFileTest extends TestCase
{
    public function testSomethingIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testConfigRead()
    {
        $connection = Config::get('database.connections');
        $this->assertTrue(!empty($connection));
    }

    public function testConfigWrite()
    {
        $now = date('Y-m-d H:i:s');
        $old = Config::get('Microweber_tests.last_test');

        Config::set('Microweber_tests.last_test', $now);
        $current = Config::get('Microweber_tests.last_test');

        Config::save();

        $get = Config::get('Microweber_tests.last_test');

        $this->assertTrue(!empty($get));
        $this->assertTrue($now == $get);
        $this->assertTrue($current == $get);
        $this->assertTrue($old != $get);
    }
    public function testVersionTxtNewLine()
    {
       $version_txt = file_get_contents(MW_ROOTPATH . '/version.txt');
       $this->assertEquals($version_txt,trim($version_txt),'version.txt file should not have new line at the end');
    }
}
