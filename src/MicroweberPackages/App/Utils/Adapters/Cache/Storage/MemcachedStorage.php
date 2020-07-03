<?php

namespace MicroweberPackages\Utils\Adapters\Cache\Storage;

use Illuminate\Cache\MemcachedStore;

class MemcachedStorage extends MemcachedStore
{
    //    public function __construct($prefix = '') {
//        $apc = new ApcWrapper();
//        return parent::__construct($apc, $prefix);
//    }
}
