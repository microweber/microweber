<?php

namespace Modules\Ai\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Ai\Services\Drivers\AiServiceInterface;

/**
 * Facade for accessing AI image services.
 *
 * @method static string|array generateImage(array $messages, array $options = []) Generate an image based on a prompt.
 * @see \Modules\Ai\Services\Drivers\AiImageServiceInterface
 */
class AiImages extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ai.images';
    }
}
