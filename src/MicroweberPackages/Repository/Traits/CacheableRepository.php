<?php


namespace MicroweberPackages\Repository\Traits;

use Closure;
use Carbon\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Model;

use function Opis\Closure\serialize as serializeClosure;
trait CacheableRepository
{
    /**
     * Cache instance
     *
     * @var CacheManager
     */
    protected static $cache = null;
    public static $disableCache= false;

    /**
     * Flush the cache after create/update/delete events.
     *
     * @var bool
     */
    protected $eventFlushCache = true;

    /**
     * Global lifetime of the cache.
     *
     * @var int
     */
    protected $cacheSeconds = 60000;

    /**
     * Set cache manager.
     *
     * @param CacheManager $cache
     */
    public static function setCacheInstance(CacheManager $cache)
    {
        self::$cache = $cache;
    }

    /**
     * Get cache manager.
     *
     * @return CacheManager
     */
    public static function getCacheInstance()
    {
        if (self::$cache === null) {
            self::$cache = app('cache');
        }

        return self::$cache;
    }

    public static function disableCache()
    {
        self::$disableCache = true;
    }

    /**
     * Determine if the cache will be skipped
     *
     * @return bool
     */
    public function skippedCache()
    {

        if(self::$disableCache){
            return true;
        }

        $is_disabled = \Config::get('microweber.disable_model_cache');
        if($is_disabled){
            return true;
        }


        return config('repositories.cache_enabled', false) === false
            || app('request')->has(config('repositories.cache_skip_param', 'skipCache')) === true;
    }

    /**
     * Get Cache key for the method
     *
     * @param string $method
     * @param mixed  $args
     * @param string $tag
     *
     * @return string
     */
    public function getCacheKey($method, $args = null, $tag =[])
    {

        if($method==='getById' and is_array($args) and isset($args[0]) and is_numeric($args[0])){

            return sprintf(
                '%s-%s--%s-%s-%s',
                app()->getLocale(),
                implode('-',$tag),
                $method,
                intval($args[0]),
                intval(is_https())
            );
        }




        $hashArgs = [];
        // Sort through arguments
        foreach ($args as $k=>$a) {
            if (is_object($a) && $a instanceof \Closure) {
                $serialized = hashClosure($a);
                $hashArgs[$k] = $serialized;
            } else {
                $hashArgs[$k] = $a;
            }
//            if ($a instanceof Model) {
//                $a = $a->getTable() . '|' . $a->getKey();
//                //$a = get_class($a) . '|' . $a->getKey();
//            }
        }





        // Create hash from arguments and query
       // $args = serializeClosure($args) . serializeClosure($this->getScopeQuery());
        $args = json_encode($hashArgs);

        return sprintf(
            '%s-%s--%s-%s-%s',
            app()->getLocale(),
            implode('-',$tag),
            $method,
            crc32($args),
            intval(is_https())
        );
    }

    /**
     * Get an item from the cache, or store the default value.
     *
     * @param string   $method
     * @param array    $args
     * @param \Closure $callback
     * @param int      $time
     *
     * @return mixed
     */
    public static $_cacheCallbackMemory = [];
    public function cacheCallback($method, $args, Closure $callback, $time = null)
    {
        // Cache disabled, just execute query & return result
        $skipCache = false;


        if ($this->skippedCache() === true) {
            $skipCache = true;
        } else if(is_array($args) and isset($args[0]) and is_array($args[0]) and isset($args[0]['no_cache']) and $args[0]['no_cache']){
            $skipCache = true;
        }

        if ($skipCache === true) {
            return call_user_func($callback);
        }

         // Use the called class name as the tag
        $tag = $this->generateCacheTags();
        $cacheKey =  $this->getCacheKey($method, $args, $tag);

        $_cacheCallback_memory_key = implode('-',$tag).$cacheKey;

        if(isset(self::$_cacheCallbackMemory[$_cacheCallback_memory_key])){
            // return from local memory to prevent cache hits
            return self::$_cacheCallbackMemory[$_cacheCallback_memory_key];
        }


        return self::$_cacheCallbackMemory[$_cacheCallback_memory_key] = self::getCacheInstance()->tags($tag)->remember(
            $cacheKey,
            $this->getCacheExpiresTime($time),
            $callback
        );
    }


    public function generateCacheTags(){
        $tag = [];
        $tag[] = 'repositories';
       // $tag[] = get_called_class();
        $tag[] = $this->getModel()->getTable();
        return $tag;
    }
    /**
     * Flush the cache for the given repository.
     *
     * @return bool
     */
    public function clearCache()
    {
        self::$_cacheCallbackMemory = [];
        self::$_loaded_models_cache_get = [];

        // Cache disabled, just ignore this
        if ($this->eventFlushCache === false || config('repositories.cache_enabled', false) === false) {
            return false;
        }
         // Use the called class name as the tag

      //  $tag = $this->generateCacheTags();

        $this->eventFlushCache = false;
       self::$disableCache = true; //disabling repository cache
   //     \Config::set('microweber.disable_model_cache', 1);

        // return self::getCacheInstance()->tags($this->generateCacheTags())->flush();
    }

    /**
     * Return the time until expires in minutes.
     *
     * @param int $time
     *
     * @return int
     */
    protected function getCacheExpiresTime($time = null)
    {
        if ($time === self::EXPIRES_END_OF_DAY) {
            return class_exists(Carbon::class)
                ? round(Carbon::now()->secondsUntilEndOfDay() / 60)
                : $this->cacheSeconds;
        }

        return $time ?: $this->cacheSeconds;
    }


}
