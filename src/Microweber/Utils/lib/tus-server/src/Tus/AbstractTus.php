<?php

namespace TusPhp\Tus;

use TusPhp\Cache\Cacheable;
use TusPhp\Cache\CacheFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
abstract class AbstractTus
{
    /** @const string Tus protocol version. */
    const TUS_PROTOCOL_VERSION = '1.0.0';
    /** @const string Name separator for partial upload. */
    const PARTIAL_UPLOAD_NAME_SEPARATOR = '_';
    /** @const string Upload type normal. */
    const UPLOAD_TYPE_NORMAL = 'normal';
    /** @const string Upload type partial. */
    const UPLOAD_TYPE_PARTIAL = 'partial';
    /** @const string Upload type final. */
    const UPLOAD_TYPE_FINAL = 'final';
    /** @const string Header Content Type */
    const HEADER_CONTENT_TYPE = 'application/offset+octet-stream';
    /** @var Cacheable */
    protected $cache;
    /** @var string */
    protected $apiPath = '/files';
    /** @var EventDispatcher */
    protected $dispatcher;
    /**
     * Set cache.
     *
     * @param mixed $cache
     *
     * @throws \ReflectionException
     *
     * @return self
     */
    public function setCache($cache)
    {
        if (is_string($cache)) {
            $this->cache = CacheFactory::make($cache);
        } elseif ($cache instanceof Cacheable) {
            $this->cache = $cache;
        }
        $prefix = 'tus:' . strtolower((new \ReflectionClass(static::class))->getShortName()) . ':';
        $this->cache->setPrefix($prefix);
        return $this;
    }
    /**
     * Get cache.
     *
     * @return Cacheable
     */
    public function getCache()
    {
        return $this->cache;
    }
    /**
     * Set API path.
     *
     * @param string $path
     *
     * @return self
     */
    public function setApiPath($path)
    {
        $this->apiPath = $path;
        return $this;
    }
    /**
     * Get API path.
     *
     * @return string
     */
    public function getApiPath()
    {
        return $this->apiPath;
    }
    /**
     * Set and get event dispatcher.
     *
     * @return EventDispatcher
     */
    public function event()
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new EventDispatcher();
        }
        return $this->dispatcher;
    }
}