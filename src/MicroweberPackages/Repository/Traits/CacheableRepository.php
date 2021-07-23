<?php


namespace MicroweberPackages\Repository\Traits;

use Closure;
use Carbon\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Model;

trait CacheableRepository
{
    /**
     * Cache instance
     *
     * @var CacheManager
     */
    protected static $cache = null;
    public $disableCache= false;

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

    /**
     * Determine if the cache will be skipped
     *
     * @return bool
     */
    public function skippedCache()
    {

        if($this->disableCache){
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
                $a = get_class($a) . '|' . $a->getKey();
            }
        }

        // Create hash from arguments and query
        $args = serialize($args) . serialize($this->getScopeQuery());

        return sprintf(
            '%s-%s@%s-%s',
            config('app.locale'),
            implode('-',$tag),
            $method,
            md5($args)
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
    public function cacheCallback($method, $args, Closure $callback, $time = null)
    {
        // Cache disabled, just execute query & return result
        if ($this->skippedCache() === true) {
            return call_user_func($callback);
        }
         // Use the called class name as the tag
        $tag = $this->generateCacheTags();

        return self::getCacheInstance()->tags($tag)->remember(
            $this->getCacheKey($method, $args, $tag),
            $this->getCacheExpiresTime($time),
            $callback
        );
    }


    public function generateCacheTags(){
        $tag = [];
        $tag[] = 'repositories';
        $tag[] = get_called_class();
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
        // Cache disabled, just ignore this
        if ($this->eventFlushCache === false || config('repositories.cache_enabled', false) === false) {
            return false;
        }
         // Use the called class name as the tag

      //  $tag = $this->generateCacheTags();

        $this->eventFlushCache = false;
        $this->disableCache = true;
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