<?php

namespace Modules\Ai\Services;

use InvalidArgumentException;
use Modules\Ai\Services\Drivers\AiServiceInterface;
use Modules\Ai\Services\Drivers\OpenAiDriver;
use Modules\Ai\Services\Drivers\OpenRouterAiDriver;

class AiService implements AiServiceInterface
{
    /**
     * The active driver instance.
     *
     * @var \Modules\Ai\Services\Drivers\AiServiceInterface
     */
    protected AiServiceInterface $driver;

    /**
     * The available driver instances.
     *
     * @var array
     */
    protected array $drivers = [];

    /**
     * Create a new AI service instance.
     *
     * @param string $defaultDriver
     * @param array $config
     */
    public function __construct(string $defaultDriver, array $config)
    {
        $this->driver = $this->createDriver($defaultDriver, $config[$defaultDriver] ?? []);
    }

    /**
     * Create a new driver instance.
     *
     * @param string $driver
     * @param array $config
     * @return \Modules\Ai\Services\Drivers\AiServiceInterface
     *
     * @throws InvalidArgumentException
     */
    protected function createDriver(string $driver, array $config): AiServiceInterface
    {
        if (isset($this->drivers[$driver])) {
            return $this->drivers[$driver];
        }

        $driverClass = match ($driver) {
            'openai' => OpenAiDriver::class,
            'openrouter' => OpenRouterAiDriver::class,

            //todo add more drivers

            default => throw new InvalidArgumentException("Driver [{$driver}] not supported."),
        };

        return $this->drivers[$driver] = new $driverClass($config);
    }

    /**
     * Send messages to chat and get a response.
     *
     *                       [
     *                           ['role' => 'system', 'content' => 'System message'],
     *                           ['role' => 'user', 'content' => 'User message'],
     *                           ['role' => 'assistant', 'content' => 'Assistant response'],
     *                           ['role' => 'function', 'name' => 'function_name', 'content' => 'Function response']
     *                       ]
     *                      - functions: Array of function definitions for the AI to call
     *                      - function_call: Optional specific function to call
     *                      - model: AI model to use
     *                      - temperature: Sampling temperature
     *                      - max_tokens: Maximum tokens in response
     * @param array $messages
     * @param array $options
     * @param array|null $schema
     * @return string|array The generated content or function call response array containing:
     *                      ['function_call' => object, 'content' => ?string]
     */
    public function sendToChat(array $messages, array $options = [], ?array $schema = null): string|array
    {




        return $this->driver->sendToChat($messages, $options);
    }

    /**
     * Get the name of the currently active AI driver.
     *
     * @return string
     */
    public function getActiveDriver(): string
    {
        return $this->driver->getActiveDriver();
    }

    /**
     * Set the active AI driver.
     *
     * @param string $driver
     * @return void
     */
    public function setActiveDriver(string $driver): void
    {
        $this->driver = $this->createDriver($driver, []);
    }
}
