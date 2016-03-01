<?php

namespace Microweber\Utils\Adapters\Cache;

use Closure;
use Microweber\Utils\Adapters\Cache\Storage\FileStorage;
use Microweber\Utils\Adapters\Cache\Storage\ApcStorage;
use Microweber\Utils\Adapters\Cache\Storage\MemcachedStorage;
use Microweber\Utils\Adapters\Cache\Storage\XCacheStorage;

class CacheStore
{
    /** @var \Microweber\Utils\Adapters\Cache\Storage\FileStorage */
    public $adapter;

    public function __construct($prefix = '')
    {
        if ($prefix == false) {
            $prefix = md5(app()->environment().site_url());
        }

        $adapter_from_config = \Config::get('microweber.cache_adapter');
        $use_file_cache = true;

        if (!$adapter_from_config || $adapter_from_config == 'file' || $adapter_from_config == 'auto') {
            $use_file_cache = true;
        } elseif ($adapter_from_config == 'apc') {
            if (function_exists('apc_fetch') || function_exists('apcu_fetch')) {
                $use_file_cache = false;
                $this->adapter = new ApcStorage($prefix);
            }
        } elseif ($adapter_from_config == 'memcached') {
            if (class_exists('Memcached', false)) {
                $use_file_cache = false;
                $this->adapter = new MemcachedStorage($prefix);
            }
        } elseif ($adapter_from_config == 'xcache') {
            if (function_exists('xcache_get')) {
                $use_file_cache = false;
                $this->adapter = new XCacheStorage($prefix);
            }
        }

        if ($use_file_cache) {
            $this->adapter = new FileStorage($prefix);
        }
    }

    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->adapter->get($key);
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     */
    public function put($key, $value, $minutes)
    {
        return $this->adapter->put($key, $value, $minutes);
    }

    /**
     * Tags for cache.
     *
     * @param string $string
     *
     * @return object
     */
    public function tags($tags)
    {
        return $this->adapter->tags($tags);
    }

    /**
     * Get an item from the cache, or store the default value.
     *
     * @param string        $key
     * @param \DateTime|int $minutes
     * @param Closure       $callback
     *
     * @return mixed
     */
    public function remember($key, $minutes, Closure $callback)
    {
        return $this->adapter->remember($key, $minutes, $callback);
    }

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param string  $key
     * @param Closure $callback
     *
     * @return mixed
     */
    public function rememberForever($key, Closure $callback)
    {
        return $this->adapter->rememberForever($key, $callback);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws \LogicException
     */
    public function increment($key, $value = 1)
    {
        return $this->adapter->increment($key, $value);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws \LogicException
     */
    public function decrement($key, $value = 1)
    {
        return $this->adapter->decrement($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function forever($key, $value)
    {
        return $this->adapter->forever($key, $value);
    }

    /**
     * Remove an item from the cache by tags.
     *
     * @param string $string
     */
    public function forgetTags($string)
    {
        return $this->adapter->forgetTags($string);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function forget($key)
    {
        return $this->adapter->forget($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @param string $tag
     */
    public function flush($all = false)
    {
        return $this->adapter->flush($all);
    }
}
