<?php

namespace Microweber\Utils\Adapters\Cache\Storage;

use Illuminate\Cache\XCacheStore;


class XCacheStorage extends XCacheStore {
    public function __construct($prefix = '')
    {
        return parent::__construct($prefix);
    }
} 