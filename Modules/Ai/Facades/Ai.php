<?php

namespace Modules\Ai\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Ai\Services\Drivers\AiServiceInterface;

/**
 * Facade for accessing AI services.
 *
 * @method static string|array sendToChat(array $messages, array $options = []) Send messages to chat and get a response.
 * @method static string getActiveDriver() Get the name of the currently active AI driver.
 * @method static void setActiveDriver(string $driver) Set the active AI driver.
 *
 * @see \Modules\Ai\Services\Drivers\AiChatServiceInterface
 */
class Ai extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ai';
    }
}
