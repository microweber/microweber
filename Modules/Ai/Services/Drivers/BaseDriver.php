<?php

namespace Modules\Ai\Services\Drivers;

use Modules\Ai\Services\Contracts\AiServiceInterface;

abstract class BaseDriver implements AiServiceInterface
{
    /**
     * The configuration options for the driver.
     *
     * @var array
     */
    protected array $config;

    /**
     * Create a new driver instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get the name of the currently active AI driver.
     *
     * @return string
     */
    public function getActiveDriver(): string
    {
        return $this->getDriverName();
    }

    /**
     * Set the active AI driver.
     * Base drivers don't need to implement this as they are already specific drivers.
     *
     * @param string $driver
     * @return void
     */
    public function setActiveDriver(string $driver): void
    {
        // Base implementation does nothing
    }

    /**
     * Get the name of this driver.
     *
     * @return string
     */
    abstract public function getDriverName(): string;
}
