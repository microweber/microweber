<?php

namespace MicroweberPackages\TaggableFileCache\Tests;

use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\TaggableFileCache\TaggableFileStore;
use MicroweberPackages\TaggableFileCache\TaggableFilesystemManager;

class TaggableFileStoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'file']);
        app('cache')->forgetDriver('file');
    }

    #[Test]
    public function it_simple(): void
    {
        $cache = app('cache');
        $cache->put('coffe', '3v1', now()->addMinutes(6));
        $this->assertEquals('3v1', $cache->get('coffe'));
    }

    #[Test]
    public function it_put_without_tags(): void
    {
        $cache = app('cache');
        $cache->put('firstName', 'Bozhidar', now()->addMinutes(6));
        $this->assertEquals('Bozhidar', $cache->get('firstName'));

        $cache->put('lastName', 'Slaveykov', now()->addMinutes(6));
        $this->assertEquals('Slaveykov', $cache->get('lastName'));
    }

    #[Test]
    public function it_get_without_tags(): void
    {
        $cache = app('cache');
        $cache->put('firstName', 'Bozhidar', now()->addMinutes(6));
        $cache->put('lastName', 'Slaveykov', now()->addMinutes(6));

        $this->assertEquals('Bozhidar', $cache->get('firstName'));
        $this->assertEquals('Slaveykov', $cache->get('lastName'));
    }

    #[Test]
    public function it_put_with_tags(): void
    {
        $cache = app('cache');
        $cache->tags(['people', 'artists'])->put('firstName', 'Peter', now()->addMinutes(9));

        $this->assertEquals('Peter', $cache->tags('people')->get('firstName'));
        $this->assertEquals('Peter', $cache->tags('artists')->get('firstName'));
        $this->assertEquals('Peter', $cache->tags('artists', 'people')->get('firstName'));
        $this->assertEquals('Peter', $cache->tags('people', 'artists')->get('firstName'));

        $this->assertEquals(null, $cache->tags('wrongTag')->get('firstName'));
    }

    #[Test]
    public function it_flush_by_tag(): void
    {
        $cache = app('cache');
        $cache->tags(['people', 'artists'])->put('firstName', 'Peter', now()->addMinutes(9));

        // Flush people tag
        $cache->tags(['people', 'artists'])->flush();

        // The caches from this tags must be null
        $this->assertEquals(null, $cache->tags('people')->get('firstName'));
        $this->assertEquals(null, $cache->tags('artists')->get('firstName'));

        // The caches from global must be null too
        $this->assertEquals(null, $cache->get('firstName'));
        $this->assertEquals(null, $cache->get('lastName'));
    }

    #[Test]
    public function it_flush_all(): void
    {
        $cache = app('cache');
        $cache->flush();

        $this->assertEquals(null, $cache->get('firstName'));
        $this->assertEquals(null, $cache->get('lastName'));

        // The caches from this tags must be null
        $this->assertEquals(null, $cache->tags('people')->get('firstName'));
        $this->assertEquals(null, $cache->tags('artists')->get('firstName'));
    }

    #[Test]
    public function it_inc_dec(): void
    {
        $cache = app('cache');
        $cache->put('someinc', 1, now()->addMinutes(6));
        $this->assertEquals(1, $cache->get('someinc'));
        $cache->increment('someinc');
        $this->assertEquals(2, $cache->get('someinc'));
        $cache->decrement('someinc');
        $this->assertEquals(1, $cache->get('someinc'));
    }

    #[Test]
    public function it_uses_taggable_file_store(): void
    {
        config(['cache.default' => 'file']);
        app('cache')->forgetDriver('file');

        $this->assertInstanceOf(TaggableFileStore::class, app('cache')->store()->getStore());
    }

    #[Test]
    public function it_scopes_cache_by_environment(): void
    {
        $cache = app('cache');
        $store = $cache->store()->getStore();

        $this->assertInstanceOf(TaggableFileStore::class, $store);

        $directory = $store->getDirectory();
        $environment = app()->environment();

        // The directory should contain the environment name
        $this->assertStringContainsString($environment, $directory);
    }

    #[Test]
    public function it_remember(): void
    {
        $cache = app('cache');
        $value = $cache->remember('remember_test', now()->addMinutes(6), function () {
            return 'remembered_value';
        });

        $this->assertEquals('remembered_value', $value);
        $this->assertEquals('remembered_value', $cache->get('remember_test'));
    }

    #[Test]
    public function it_forever(): void
    {
        $cache = app('cache');
        $cache->forever('forever_test', 'forever_value');
        $this->assertEquals('forever_value', $cache->get('forever_test'));
    }

    #[Test]
    public function it_forget(): void
    {
        $cache = app('cache');
        $cache->put('forget_test', 'value', now()->addMinutes(6));
        $this->assertEquals('value', $cache->get('forget_test'));

        $cache->forget('forget_test');
        $this->assertNull($cache->get('forget_test'));
    }

    #[Test]
    public function it_has(): void
    {
        $cache = app('cache');
        $store = $cache->store()->getStore();

        $store->put('has_test', 'value', now()->addMinutes(6));
        $this->assertTrue($store->has('has_test'));
    }
}