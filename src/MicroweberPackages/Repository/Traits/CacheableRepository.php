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
        // Sort through arguments
        foreach ($args as &$a) {
            if ($a instanceof Model) {
                $a = $a->getTable() . '|' . $a->getKey();
                //$a = get_class($a) . '|' . $a->getKey();
            }
        }

        // Create hash from arguments and query
        $args = serializeClosure($args) . serializeClosure($this->getScopeQuery());

        return sprintf(
            '%s-%s--%s-%s',
            config('app.locale'),
            implode('-',$tag),
            $method,
            crc32($args)
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
        if ($this->skippedCache() === true) {
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
    public function flushCache()
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
       // return self::getCacheInstance()->tags($tag)->flush();
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
