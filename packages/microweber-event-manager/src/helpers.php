<?php

use MicroweberPackages\Event\Facades\EventManager;
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

if (!function_exists('event_trigger')) {
    /**
     * Trigger an event and return the collected listener responses.
     *
     * @return list<mixed>|null
     */
    function event_trigger(string $eventName, mixed $data = false): ?array
    {
        /** @var \MicroweberPackages\Event\EventService $manager */
        $manager = EventManager::getFacadeRoot();

        return $manager->trigger($eventName, $data);
    }
}

if (!function_exists('event_bind')) {
    /**
     * Bind a listener to an event name.
     */
    function event_bind(string $eventName, callable|string $callback): void
    {
        /** @var \MicroweberPackages\Event\EventService $manager */
        $manager = EventManager::getFacadeRoot();
        $manager->on($eventName, $callback);
    }
}

if (!function_exists('event_unbind')) {
    /**
     * Remove all listeners for a specific event.
     */
    function event_unbind(string $eventName): void
    {
        /** @var \MicroweberPackages\Event\EventService $manager */
        $manager = EventManager::getFacadeRoot();
        $manager->unbind($eventName);
    }
}

if (!function_exists('event_unbind_all')) {
    /**
     * Remove every listener for every event, releasing all closures from memory.
     */
    function event_unbind_all(): void
    {
        /** @var \MicroweberPackages\Event\EventService $manager */
        $manager = EventManager::getFacadeRoot();
        $manager->unbindAll();
    }
}