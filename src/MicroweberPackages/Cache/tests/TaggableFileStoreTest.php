<?php



use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Cache\CacheFileHandler\Facades\Cache;

use Tests\TestCase;

class TaggableFileStoreTest extends TestCase
{

    #[Test]

    public function it_simple(): void {
        Cache::put('coffe', '3v1', now()->addMinutes(6));
        $this->assertEquals('3v1', Cache::get('coffe'));
    }

    #[Test]

    public function it_put_without_tags(): void {
        Cache::put('firstName', 'Bozhidar', now()->addMinutes(6));
        $this->assertEquals('Bozhidar', Cache::get('firstName'));

        Cache::put('lastName', 'Slaveykov', now()->addMinutes(6));

        $this->assertEquals('Slaveykov', Cache::get('lastName'));

    }

    #[Test]

    public function it_get_without_tags(): void {
        $this->assertEquals('Bozhidar', Cache::get('firstName'));
        $this->assertEquals('Slaveykov', Cache::get('lastName'));
    }

    #[Test]

    public function it_put_with_tags(): void {
        Cache::tags(['people', 'artists'])->put('firstName', 'Peter', now()->addMinutes(9));

        $this->assertEquals('Peter', Cache::tags('people')->get('firstName'));
        $this->assertEquals('Peter', Cache::tags('artists')->get('firstName'));
        $this->assertEquals('Peter', Cache::tags('artists', 'people')->get('firstName'));
        $this->assertEquals('Peter', Cache::tags('people', 'artists')->get('firstName'));

        $this->assertEquals(NULL, Cache::tags('wrongTag')->get('firstName'));
    }


//    public function testSpeedOfGetingCacheWithTags()
//    {
//
//        $isSpeetTestOk = true;
//        $before = microtime(true);
//
//        for ($i = 1; $i <= 1000; $i++) {
//            Cache::tags('people')->get('firstName');
//            Cache::tags('artists')->get('firstName');
//            Cache::tags('wrongTag')->get('firstName');
//          //  $this->assertEquals('Peter', Cache::tags('people')->get('firstName'));
//           // $this->assertEquals('Peter', Cache::tags('artists')->get('firstName'));
//           // $this->assertEquals(NULL, Cache::tags('wrongTag')->get('firstName'));
//        }
//
//        $after = microtime(true);
//        if (($after-$before) > 1.50) {
//            $isSpeetTestOk = false;
//        }
//
//        $this->assertEquals(true, $isSpeetTestOk);
//    }


    #[Test]


    public function it_flush_by_tag(): void {
        // Flush people tag
        Cache::tags(['people', 'artists'])->flush(); // This will be delete all asociated files with tag people

        // The caches from this tags must be null
        $this->assertEquals(NULL, Cache::tags('people')->get('firstName'));
        $this->assertEquals(NULL, Cache::tags('artists')->get('firstName'));


        // The caches form global must be valid
        $this->assertEquals(NULL, Cache::get('firstName'));
        $this->assertEquals(NULL, Cache::get('lastName'));

    }


    #[Test]


    public function it_flush_all(): void {
        Cache::flush(); // This will be delete all asociated files with tag people

        $this->assertEquals(NULL, Cache::get('firstName'));
        $this->assertEquals(NULL, Cache::get('lastName'));


        // The caches from this tags must be null
        $this->assertEquals(NULL, Cache::tags('people')->get('firstName'));
        $this->assertEquals(NULL, Cache::tags('artists')->get('firstName'));
    }

    #[Test]

    public function it_inc_dec(): void {
        Cache::put('someinc', 1, now()->addMinutes(6));
        $this->assertEquals(1, Cache::get('someinc'));
        Cache::increment('someinc');
        $this->assertEquals(2, Cache::get('someinc'));
        Cache::decrement('someinc');
        $this->assertEquals(1, Cache::get('someinc'));

    }

}
