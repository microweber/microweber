<?php

class TaggableFileCacheServiceProviderTest extends BaseTest
{
	public function testCacheIsTaggableFileCacheWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\Cache\TaggableFileStore::class, app('cache')->store()->getStore());
	}

}