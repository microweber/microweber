<?php

namespace FunctionsTest;


class CacheTest extends \PHPUnit_Framework_TestCase
{


    public function testCacheReadWrite()
    {


        $data = array('something' => 'some_value');
        $cache_id = 'my_cache_id';


        //procedural
        $saved_cache = cache_save($data, $cache_id, 'my_cache_group');
        $cache_content = cache_get($cache_id, 'my_cache_group');

        //PHPUnit
        $this->assertEquals(true, $saved_cache);
        $this->assertEquals(true, isset($cache_content['something']));
        $this->assertEquals($data, $cache_content);


    }

    public function testCacheDelete()
    {
        $data = array('something' => 'some_value');
        $cache_id = 'my_cache_id';

        // procedural
        cache_save($data, $cache_id, 'my_cache_group');
        cache_clear('my_cache_group');
        //check if deleted
        $cache_content = cache_get($cache_id, 'my_cache_group');

        //PHPUnit
        $this->assertEquals(false, $cache_content);


    }




}