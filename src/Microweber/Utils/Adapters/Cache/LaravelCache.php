<?php
namespace Microweber\Utils\Adapters\Cache;

use Illuminate\Cache\tags;
use Illuminate\Support\Facades\Cache;

class LaravelCache {


    public $ttl = 30;
    public $support_tags = false;
    public $app;
    public $cache_hits = array();

    function __construct($app) {
        $this->app = $app;

        $driver = $this->app['config']['cache.driver'];

        if ($driver=='database'){
            $this->support_tags = false;
        } else {
            $this->support_tags = true;
        }

    }


    public function cache_group($cache_group) {
        if (is_string($cache_group)){
            $cache_group = str_replace('\\', '/', $cache_group);
            $cache_group = explode('/', $cache_group);
            $cg = $cache_group[0];

            return $cg;
        }

    }

    /**
     * Stores your data in the cache.
     * It can store any value that can be serialized, such as strings, array, etc.
     *
     * @param mixed  $data_to_cache
     *            your data, anything that can be serialized
     * @param string $cache_id
     *            id of the cache, you must define it because you will use it later to
     *            retrieve the cached content.
     * @param string $cache_group
     *            (default is 'global') - this is the subfolder in the cache dir.
     *
     * @internal param bool $cache_storage_type
     * @return boolean
     * @package  Cache
     * @example
     * <code>
     * //store custom data in cache
     * $data = array('something' => 'some_value');
     * $cache_id = 'my_cache_id';
     * $cache_content = mw()->cache->save($data, $cache_id, 'my_cache_group');
     * </code>
     *
     */
    public function save($data_to_cache, $cache_id, $cache_group = 'global', $expiration = false) {
        if (!$this->support_tags){
            return;
        }
        $cache_group = $this->cache_group($cache_group);
        if ($expiration!=false){
            Cache::tags($cache_group)->put($cache_id, $data_to_cache, intval($expiration));
        } else {
            Cache::tags($cache_group)->put($cache_id, $data_to_cache, $this->ttl);
        }
    }

    /**
     *  Gets the data from the cache.
     *
     *  If data is not found it return false
     *     *
     *
     * @param string $cache_id    id of the cache
     * @param string $cache_group (default is 'global') - this is the subfolder in the cache dir.
     *
     * @param bool   $timeout
     *
     * @return  mixed returns array of cached data or false
     * @package  Cache
     * @example
     *           <code>
     *
     * $cache_id = 'my_cache_'.crc32($sql_query_string);
     * $cache_content = mw()->cache->get($cache_id, 'my_cache_group');
     *
     * </code>
     */
    public function get($cache_id, $cache_group = 'global', $timeout = false) {
        $cache_group = $this->cache_group($cache_group);

        if (!isset($this->cache_hits[ $cache_group ])){
            $this->cache_hits[ $cache_group ] = array();
        }
        if (!isset($this->cache_hits[ $cache_group ][ $cache_id ])){
            $this->cache_hits[ $cache_group ][ $cache_id ] = 0;
        }
        $this->cache_hits[ $cache_group ][ $cache_id ] ++;
        if (!$this->support_tags){
            return;
        }

        return Cache::tags($cache_group)->get($cache_id);
    }

    /**
     * Deletes cache for given $cache_group recursively.
     *
     * @param string $cache_group
     *            (default is 'global') - this is the subfolder in the cache dir.
     *
     * @return boolean
     *
     * @package Cache
     * @example
     * <code>
     * //delete the cache for the content
     *  mw()->cache->delete("content");
     *
     * //delete the cache for the content with id 1
     *  mw()->cache->delete("content/1");
     *
     * //delete the cache for users
     *  mw()->cache->delete("users");
     *
     * //delete the cache for your custom table eg. my_table
     * mw()->cache->delete("my_table");
     * </code>
     */
    public function delete($cache_group = 'global') {
        $cache_group = $this->cache_group($cache_group);
        if (!$this->support_tags){
            return;
        }

        return Cache::tags($cache_group)->flush();
    }


    /**
     * Clears all cache data
     *
     * @example
     *          <code>
     *          //delete all cache
     *          mw()->cache->clear();
     *          </code>
     * @return boolean
     * @package Cache
     */

    public function clear() {
        return Cache::flush(true);
    }


    /**
     * Prints cache debug information
     *
     * @return array
     * @package Cache
     * @example
     * <code>
     * //get cache items info
     *  $cached_items = mw()->cache->debug();
     * print_r($cached_items);
     * </code>
     */
    public function debug() {
        $items = $this->cache_hits;

        return $items;
    }

}


