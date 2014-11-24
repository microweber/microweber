<?php

namespace Weber\Providers;

use Weber\Utils\Adapters\Cache\LaravelCache;


/**
 * Cache class
 *
 * These functions will allow you to save and get data from the MW cache system
 *
 * @package Cache
 * @category Cache
 * @desc  These functions will allow you to save and get data from the MW cache system
 */


class CacheManager
{

    /**
     * An instance of the Weber Application class
     *
     * @var $app
     */
    public $app;
    /**
     * An instance of the cache adapter to use
     *
     * @var $adapter
     */
    public $adapter;

    function __construct($app = null)
    {



        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->adapter = new LaravelCache($app);


    }



    /**
     * Stores your data in the cache.
     * It can store any value that can be serialized, such as strings, array, etc.
     *
     * @param mixed $data_to_cache
     *            your data, anything that can be serialized
     * @param string $cache_id
     *            id of the cache, you must define it because you will use it later to
     *            retrieve the cached content.
     * @param string $cache_group
     *            (default is 'global') - this is the subfolder in the cache dir.
     *
     * @internal param bool $cache_storage_type
     * @return boolean
     * @package Cache
     * @example
     * <code>
     * //store custom data in cache
     * $data = array('something' => 'some_value');
     * $cache_id = 'my_cache_id';
     * $cache_content = wb()->cache->save($data, $cache_id, 'my_cache_group');
     * </code>
     *
     */
    public function save($data_to_cache, $cache_id, $cache_group = 'global')
    {
        return $this->adapter->save($data_to_cache, $cache_id, $cache_group);

    }

    /**
     *  Gets the data from the cache.
     *
     *  If data is not found it return false
     *     *
     * @param string $cache_id id of the cache
     * @param string $cache_group (default is 'global') - this is the subfolder in the cache dir.
     *
     * @param bool $timeout
     * @internal param bool $cache_storage_type You can pass custom cache object or leave false.
     * @return  mixed returns array of cached data or false
     * @package Cache
     * @example
     * <code>
     *
     * $cache_id = 'my_cache_'.crc32($sql_query_string);
     * $cache_content = wb()->cache->get($cache_id, 'my_cache_group');
     *
     * </code>
     */
    public function get($cache_id, $cache_group = 'global', $timeout = false)
    {
        return $this->adapter->get($cache_id, $cache_group, $timeout);
    }

    /**
     * Deletes cache for given $cache_group recursively.
     *
     * @param string $cache_group
     *            (default is 'global') - this is the subfolder in the cache dir.
     * @internal param bool $cache_storage_type
     * @return boolean
     *
     * @package Cache
     * @example
     * <code>
     * //delete the cache for the content
     *  wb()->cache->delete("content");
     *
     * //delete the cache for the content with id 1
     *  wb()->cache->delete("content/1");
     *
     * //delete the cache for users
     *  wb()->cache->delete("users");
     *
     * //delete the cache for your custom table eg. my_table
     * wb()->cache->delete("my_table");
     * </code>
     */
    public function delete($cache_group = 'global')
    {
        $this->adapter->delete($cache_group);
    }

    /**
     * Clears all cache data
     * @example
     * <code>
     * //delete all cache
     *  wb()->cache->clear();
     * </code>
     * @return boolean
     * @package Cache
     */

    public function clear()
    {
        return $this->adapter->clear();
    }



    /**
     * Prints cache debug information
     *
     * @return array
     * @package Cache
     * @example
     * <code>
     * //get cache items info
     *  $cached_items = wb()->cache->debug();
     * print_r($cached_items);
     * </code>
     */
    public function debug()
    {
        return $this->adapter->debug();
    }

}
