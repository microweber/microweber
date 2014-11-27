<?php
namespace Microweber\Utils\Adapters\Cache;

use Illuminate\Cache\tags;
use Illuminate\Support\Facades\Cache;

class LaravelCache
{


    public $ttl = 30;
    public $support_tags = false;
    public $app;

    function __construct($app)
    {
        $this->app = $app;

        $driver = $this->app['config']['cache.driver'];

        if ($driver == 'file' or $driver == 'database') {
            $this->support_tags = false;
        } else {
            $this->support_tags = true;
        }

    }

    private $mkdir_cache = array();

    public function  cache_mkdir($cache_group)
    {

        if (isset($mkdir_cache[$cache_group])) {
            return;
        }
        $mkdir_cache[$cache_group] = true;
        $path = mw_cache_path() . $cache_group;
        $path = normalize_path($path, 1);
        if (!is_dir($path)) {
            mkdir_recursive($path);
        }


    }

    public function cache_group($cache_group)
    {

        $cache_group = explode('/',$cache_group);

        if(isset($cache_group[1])){

            $group = str_replace('/', '-', $cache_group[1]);
            $group = str_replace('\\', '-', $group);
            $cache_group = $cache_group[0].'-'.$group;
        }

        return $cache_group;
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
     * $cache_content = mw()->cache->save($data, $cache_id, 'my_cache_group');
     * </code>
     *
     */
    public function save($data_to_cache, $cache_id, $cache_group = 'main')
    {

        if (!$this->support_tags) {
            return;
            return Cache::put($cache_group . $cache_id, $data_to_cache, $this->ttl);
        }
        $cache_group = $this->cache_group($cache_group);

        Cache::tags($cache_group)->put($cache_id, $data_to_cache, $this->ttl);


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
     * $cache_content = mw()->cache->get($cache_id, 'my_cache_group');
     *
     * </code>
     */
    public function get($cache_id, $cache_group = 'main', $timeout = false)
    {
        $cache_group = $this->cache_group($cache_group);
        if (!$this->support_tags) {
            return;
            return Cache::get($cache_group . $cache_id);
        }
        return Cache::tags($cache_group)->get($cache_id);


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
    public function delete($cache_group = 'main')
    {
        $cache_group = $this->cache_group($cache_group);

        if (!$this->support_tags) {
            return;
            return $this->delete_from_memory($cache_group);
        }

        return Cache::tags($cache_group)->flush();
    }

    public function delete_from_memory($cache_group)
    {

        //  dd($cache_group);


        // return Cache::flush();
//       $obj = Cache::getFacadeApplication();
//        $driver = $driver = Cache::driver();
//        $class_methods = get_class_methods($driver);
//
//        foreach ($class_methods as $method_name) {
//            echo "$method_name\n";
//        }
//
//        dd($driver);
//        dd($cache_group);
//        foreach (Cache::getMemory() as $cacheKey => $cacheValue) {
//            d($cacheKey);
//            $items[$cacheKey] = $cacheValue;
//            //Cache::forget($cacheKey);
//        }


    }

    /**
     * Clears all cache data
     * @example
     * <code>
     * //delete all cache
     *  mw()->cache->clear();
     * </code>
     * @return boolean
     * @package Cache
     */

    public function clear()
    {
        return Cache::flush();

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
    public function debug()
    {

        $items = array();
        foreach (Cache::getMemory() as $cacheKey => $cacheValue) {
            $items[$cacheKey] = $cacheValue;
            //Cache::forget($cacheKey);
        }
        return $items;
    }

}



