<?php

namespace TusPhp\Cache;

use Carbon\Carbon;
use TusPhp\Config;
use Predis\Client as RedisClient;
class RedisStore extends AbstractCache
{
    /** @var RedisClient */
    protected $redis;
    /**
     * RedisStore constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $options = empty($options) ? Config::get('redis') : $options;
        $this->redis = new RedisClient($options);
    }
    /**
     * Get redis.
     *
     * @return RedisClient
     */
    public function getRedis()
    {
        return $this->redis;
    }
    /**
     * {@inheritDoc}
     */
    public function get($key, $withExpired = false)
    {
        $prefix = $this->getPrefix();
        if (false === strpos($key, $prefix)) {
            $key = $prefix . $key;
        }
        $contents = $this->redis->get($key);
        $contents = json_decode($contents, true);
        if ($withExpired) {
            return $contents;
        }
        $isExpired = Carbon::parse($contents['expires_at'])->lt(Carbon::now());
        return $isExpired ? null : $contents;
    }
    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $contents = !empty($this->get($key)) ? $this->get($key) : [];
        if (is_array($value)) {
            $contents = $value + $contents;
        } else {
            array_push($contents, $value);
        }
        $status = $this->redis->set($this->getPrefix() . $key, json_encode($contents));
        return 'OK' === $status->getPayload();
    }
    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $prefix = $this->getPrefix();
        if (false === strpos($key, $prefix)) {
            $key = $prefix . $key;
        }
        return $this->redis->del([$key]) > 0;
    }
    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        return $this->redis->keys($this->getPrefix() . '*');
    }
}