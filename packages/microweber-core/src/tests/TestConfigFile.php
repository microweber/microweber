<?php

namespace MicroweberPackages\Core\tests;

use Illuminate\Support\Facades\Config;
class TestConfigFile extends TestCase
{
    public function testSomethingIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testOptions()
    {
        $data = array();
        $data['today'] = date('Y-m-d');
        $data['option_key'] = 'a_test';

        $data = json_encode($data);
        $save = save_option($data);

        $get = get_option('a_test', $data);
       // dd($get);
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
}
