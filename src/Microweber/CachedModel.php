<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;


abstract class CachedModel extends Eloquent {
// Time To Live
    public static $ttl = 5;
    //change from protected
    public function _get($id, $columns = array('*'))
        //change from public
    {
        dd(__FILE__.__LINE__);
    }

    public static function _find($id, $columns = array('*'))
        //change from public
    {

        $cache_key = static::_get_cache_key($id);

        // Check for cache
        if (Cache::has($cache_key))
        {
            return Cache::get($cache_key);
        }

        // No cache, so lets get it from the DB and save to cache
        $result = parent::_find($id, $columns);

        if ($result)
        {
            Cache::put($cache_key, $result, static::$ttl);  // Save for 5 minutes
            //change from $this->ttl
        }

        return $result;

    }


    /**
     * Get all of the models in the database from cache or store them to cache.
     *
     * @return array
     */
    public static function all($columns = array())
    {
        $cache_key = with(new static)->_get_cache_key('all');

        // Check for cache
        if (Cache::has($cache_key))
        {
            return Cache::get($cache_key);
        }

        // No cache, so lets get it from the DB and save to cache
        $result = with(new static)->query()->get();
        if ($result)
        {
            Cache::put($cache_key, $result, with(new static)->ttl);  // Save for 5 minutes
        }
        return $result;
    }

    public function save(array $options = array())
    {
        $result = parent::save($options);
        if ($result) {
            Cache::forget(static::_get_cache_key($this->id));
        }
        return $result;
    }


    public function delete()
    {
        $result = parent::delete();
        if ($result) {
            Cache::forget(static::_get_cache_key($this->id));
        }
        return $result;
    }

    private static function _get_cache_key($id)
        //change from private
    {
        return 'model_'.static::$table.'_'.$id;
        //change from static::table()
    }

}
