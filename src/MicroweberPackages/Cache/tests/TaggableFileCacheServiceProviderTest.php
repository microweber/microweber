<?php



use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaggableFileCacheServiceProviderTest extends TestCase
{
	#[Test]
	public function it_cache_is_taggable_file_cache_when_using(): void {

		config(['cache.default' => 'file']);
		app('cache')->forgetDriver('file');

		$this->assertInstanceOf(\MicroweberPackages\Cache\TaggableFileStore::class, app('cache')->store()->getStore());
	}

}
