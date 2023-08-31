<?php

class TaggableFileCacheServiceProviderTest extends \MicroweberPackages\Core\tests\TestCase
{
	public function testCacheIsTaggableFileCacheWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Cache\TaggableFileStore::class, app('cache')->store()->getStore());
	}

}
