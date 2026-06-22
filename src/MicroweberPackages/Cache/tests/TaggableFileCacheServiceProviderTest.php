<?php



use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaggableFileCacheServiceProviderTest extends TestCase
{
	#[Test]
	public function it_cache_is_taggable_file_cache_when_using(): void {

		config(['cache.default' => 'file']);
		app('cache')->forgetDriver('file');

		// The canonical implementation now lives in the standalone package
		// microweber-packages/taggable-file-cache. The old MicroweberPackages\Cache\TaggableFileStore
		// is a back-compat subclass alias, so the active (parent) store satisfies the package FQCN.
		$this->assertInstanceOf(\MicroweberPackages\TaggableFileCache\TaggableFileStore::class, app('cache')->store()->getStore());
	}

}
