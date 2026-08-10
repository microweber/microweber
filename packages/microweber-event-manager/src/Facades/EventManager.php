<?php

namespace MicroweberPackages\Event\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Event\EventService;

/**
 * EventManager facade — greppable public API for event bind/trigger.
 *
 * @method static void on(string $eventName, callable|string $callback)
 * @method static list<mixed>|null trigger(string $eventName, mixed $data = false)
 * @method static array<string, mixed> response(string $eventName, array<string, mixed> $criteria)
 * @method static void unbind(string $eventName)
 * @method static void unbindAll()
 * @method static bool hasListeners(string $eventName)
 * @method static bool registerShutdownEvent(callable $callback, mixed ...$args)
 *
 * @see \MicroweberPackages\Event\EventService
 * @mixin \MicroweberPackages\Event\EventService
 */
class EventManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EventService::class;
    }
}
