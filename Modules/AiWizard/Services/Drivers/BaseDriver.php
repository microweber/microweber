<?php

namespace Modules\AiWizard\Services\Drivers;

use Modules\AiWizard\Services\Contracts\AiServiceInterface;

abstract class BaseDriver implements AiServiceInterface
{
    /**
     * Configuration for the driver
     *
     * @var array
     */
    protected array $config;

    /**
     * Create a new driver instance
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
}
