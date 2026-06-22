<?php

namespace MicroweberPackages\TaggableFileCache;

use Illuminate\Filesystem\Filesystem;
use MicroweberPackages\TaggableFileCache\CacheFileHandler\MemoryCacheFileHandler;

class TaggableFilesystemManager extends Filesystem
{
    public $cachedDataMemory = [];
    public $tagMapCacheMemory = [];
    public $tagMapPathsCacheMemory = [];
    public $cacheHandler = null;

    public function __construct()
    {
        $this->cacheHandler = new MemoryCacheFileHandler();
    }
}