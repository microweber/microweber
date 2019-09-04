<?php

namespace TusPhp\Cache;

class CacheFactory
{
    /**
     * Make cache.
     *
     * @param string $type
     *
     * @static
     *
     * @return Cacheable
     */
    public static function make($type = 'file')
    {
        switch ($type) {
            case 'redis':
                return new RedisStore();
        }
        return new FileStore();
    }
}