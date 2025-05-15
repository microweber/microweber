<?php

namespace Modules\Ai\Services\Drivers;

abstract class BaseDriver implements AiChatServiceInterface
{

    use AiParseJsonTrait;

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

    abstract function sendToChat(array $messages, array $options = []): string|array;


    /**
     * Get the name of this driver.
     *
     * @return string
     */
    abstract public function getDriverName(): string;
}
