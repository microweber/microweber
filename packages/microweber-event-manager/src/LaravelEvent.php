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

/**
 * Instance-based event dispatcher.
 *
 * All listener state is held in instance properties so the garbage collector
 * can reclaim it when the owning container is flushed (e.g. between tests).
 */
class LaravelEvent
{
    /** @var array<string, list<callable|string>> */
    private array $hooks = [];

    public function listen(string $eventName, callable|string $callback): void
    {
        $this->hooks[$eventName][] = $callback;
    }

    /**
     * Fire an event and return the collected listener responses.
     *
     * @return list<mixed>|null
     */
    public function fire(string $eventName, mixed $data = false): ?array
    {
        if (!isset($this->hooks[$eventName])) {
            return null;
        }

        $fns = $this->hooks[$eventName];
        $resp = [];

        foreach ($fns as $fn) {
            if (is_callable($fn)) {
                $resp[] = call_user_func($fn, $data);
            }
        }

        return $resp;
    }

    /**
     * Remove all listeners for a specific event.
     */
    public function unbind(string $eventName): void
    {
        unset($this->hooks[$eventName]);
    }

    /**
     * Remove every listener for every event.
     */
    public function unbindAll(): void
    {
        $this->hooks = [];
    }

    /**
     * Check whether any listeners are registered for an event.
     */
    public function hasListeners(string $eventName): bool
    {
        return !empty($this->hooks[$eventName]);
    }

    /**
     * Return the current hooks array (mainly for testing/debugging).
     *
     * @return array<string, list<callable|string>>
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }
}