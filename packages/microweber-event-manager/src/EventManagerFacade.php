<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Event;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void on(string $eventName, callable|string $callback)
 * @method static list<mixed>|null trigger(string $eventName, mixed $data = false)
 * @method static array<string, mixed> response(string $eventName, array<string, mixed> $criteria)
 * @method static void unbind(string $eventName)
 * @method static void unbindAll()
 * @method static bool hasListeners(string $eventName)
 *
 * @see \MicroweberPackages\Event\Event
 */
class EventManagerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'event_manager';
    }
}