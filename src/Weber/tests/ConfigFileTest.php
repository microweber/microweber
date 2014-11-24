<?php

class ConfigFileTest extends TestCase
{

    public function testSomethingIsTrue()
    {
        $this->assertTrue(true);
    }


    public function testConfigRead()
    {
        $connection = Config::get('database.connections');
        $this->assertTrue(true, !empty($connection));
    }

    public function testConfigWrite()
    {
        $now = date("Y-m-d H:i:s");
        $old = Config::get('Weber_tests.last_test');

        Config::set('Weber_tests.last_test', $now);
        $current = Config::get('Weber_tests.last_test');

        Config::save();

        $get = Config::get('Weber_tests.last_test');

        $this->assertTrue(true, !empty($get));
        $this->assertTrue(true, $now == $get);
        $this->assertTrue(true, $current == $get);
        $this->assertTrue(true, $old != $get);
    }

}