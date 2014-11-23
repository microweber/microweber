<?php

namespace ClassTest;


class CacheTest extends \PHPUnit_Framework_TestCase
{


    public function testCacheReadWrite()
    {


        $data = array('something' => 'some_value');

        $cache_id = 'my_cache_id_'.rand();
        $cache_group = 'my_test_group';

        $saved_cache = mw('cache')->save($data, $cache_id, $cache_group);
        $cache_content = mw('cache')->get($cache_id, $cache_group);


        $this->assertEquals(true, $saved_cache);
        $this->assertEquals(true, isset($cache_content['something']));
        $this->assertEquals($data, $cache_content);

    }

    public function testCacheDelete()
    {
        $data = array('something' => 'some_value');

        $cache_id = 'my_cache_id_'.rand();
        $cache_group = 'my_test_group';


        $saved_cache = mw('cache')->save($data, $cache_id, $cache_group);


        mw('cache')->delete($cache_group);

        $cache_content = mw('cache')->get($cache_id,$cache_group);


        $this->assertEquals(true, $saved_cache);

        $this->assertEquals(false, $cache_content);
    }

}