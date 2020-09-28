<?php

class TaggableFileStoreTest extends BaseTest
{
    public function testSimple()
    {
        Cache::put('coffe', '3v1', now()->addMinutes(3));

        $this->assertEquals('3v1', Cache::get('coffe'));

    }

    public function testPutWithoutTags()
    {
        Cache::put('firstName', 'Bozhidar', now()->addMinutes(3));
        $this->assertEquals('Bozhidar', Cache::get('firstName'));

        Cache::put('lastName', 'Slaveykov', now()->addMinutes(6));
        $this->assertEquals('Slaveykov', Cache::get('lastName'));

    }

    public function testGetWithoutTags()
    {
        $this->assertEquals('Bozhidar', Cache::get('firstName'));
        $this->assertEquals('Slaveykov', Cache::get('lastName'));
    }

    public function testPutWithTags()
    {
        Cache::tags(['people', 'artists'])->put('firstName', 'Peter', now()->addMinutes(9));

        $this->assertEquals('Peter', Cache::tags('people')->get('firstName'));
        $this->assertEquals('Peter', Cache::tags('artists')->get('firstName'));
        $this->assertEquals('Peter', Cache::tags('artists', 'people')->get('firstName'));
        $this->assertEquals('Peter', Cache::tags('people', 'artists')->get('firstName'));

        $this->assertEquals(NULL, Cache::tags('wrongTag')->get('firstName'));
    }


    public function testFlushByTag()
    {
        // Flush people tag
        Cache::tags(['people', 'artists'])->flush(); // This will be delete all asociated files with tag people

        // The caches from this tags must be null
        $this->assertEquals(NULL, Cache::tags('people')->get('firstName'));
        $this->assertEquals(NULL, Cache::tags('artists')->get('firstName'));


        // The caches form global must be valid
        $this->assertEquals('Bozhidar', Cache::get('firstName'));
        $this->assertEquals('Slaveykov', Cache::get('lastName'));

    }

    public function testFlushAll()
    {
        Cache::flush(); // This will be delete all asociated files with tag people

        $this->assertEquals(NULL, Cache::get('firstName'));
        $this->assertEquals(NULL, Cache::get('lastName'));
    }

}
