<?php

namespace MicroweberPackages\Cache;

use Illuminate\Filesystem\Filesystem;

class TaggableFilesystemManager extends Filesystem
{

    public $cachedDataMemory = [];
    public $tagMapCacheMemory = [];
    public $tagMapPathsCacheMemory = [];

}