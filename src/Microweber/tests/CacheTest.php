<?php

class CacheTest extends TestCase
{


    public function testCache()
    {

        $tags = array('phpunit', 'phpunit_second_tag');

        $now = date('Ymdhis');


        $key = 'phpunit-' . $now;


        Cache::tags($tags)->put($key, $now, 10);

        $some = Cache::tags($tags)->get($key);
        d($some);

        Cache::tags($tags)->flush();
        $some = Cache::tags($tags)->get($key);
    d($some);
//
//        Cache::tags('people')->flush();

        dd('aaaaaaaaa');

    }


}