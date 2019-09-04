<?php

namespace TusPhp\Middleware;

class Middleware
{
    /** @var array */
    protected $globalMiddleware = [];
    /**
     * Middleware constructor.
     */
    public function __construct()
    {
        $this->globalMiddleware = [GlobalHeaders::class => new GlobalHeaders(), Cors::class => new Cors()];
    }
    /**
     * Get registered middleware.
     *
     * @return array
     */
    public function list()
    {
        return $this->globalMiddleware;
    }
    /**
     * Set middleware.
     *
     * @param array $middleware
     *
     * @return Middleware
     */
    public function add(...$middleware)
    {
        foreach ($middleware as $m) {
            if ($m instanceof TusMiddleware) {
                $this->globalMiddleware[get_class($m)] = $m;
            } elseif (is_string($m)) {
                $this->globalMiddleware[$m] = new $m();
            }
        }
        return $this;
    }
    /**
     * Skip middleware.
     *
     * @param array ...$middleware
     *
     * @return Middleware
     */
    public function skip(...$middleware)
    {
        foreach ($middleware as $m) {
            unset($this->globalMiddleware[$m]);
        }
        return $this;
    }
}