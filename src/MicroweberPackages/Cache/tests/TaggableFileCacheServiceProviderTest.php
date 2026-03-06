<?php



use PHPUnit\Framework\Attributes\Test;
class TaggableFileCacheServiceProviderTest extends \MicroweberPackages\Core\tests\TestCase
{
	#[Test]
	public function it_cache_is_taggable_file_cache_when_using(): void {

		$this->assertInstanceOf(\MicroweberPackages\Cache\TaggableFileStore::class, app('cache')->store()->getStore());
	}

}
