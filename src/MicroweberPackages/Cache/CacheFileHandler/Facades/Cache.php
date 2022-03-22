<?php

namespace MicroweberPackages\Cache\CacheFileHandler\Facades;

use Illuminate\Support\Facades\Facade;

class Cache extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'cache';
    }

}
