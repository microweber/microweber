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
 * High-level event manager.
 *
 * Wraps a {@see LaravelEvent} adapter using instance (non-static) state so the
 * garbage collector can reclaim all listeners when the container is flushed
 * (e.g. between PHPUnit tests).
 */
class Event
{
    private LaravelEvent $adapter;

    /** @var list<list<mixed>> */
    private array $callbacks = [];

    public function __construct(?LaravelEvent $adapter = null)
    {
        $this->adapter = $adapter ?? new LaravelEvent();

        register_shutdown_function([$this, 'callRegisteredShutdown']);
    }

    /**
     * Bind a listener to an event name.
     */
    public function on(string $eventName, callable|string $callback): void
    {
        $this->adapter->listen($eventName, $callback);
    }

    /**
     * Emit an event and return the collected listener responses.
     *
     * @return list<mixed>|null
     */
    public function trigger(string $eventName, mixed $data = false): ?array
    {
        $args = func_get_args();
        array_shift($args);

        if (count($args) === 1) {
            $args = $args[0];
        }

        return $this->adapter->fire($eventName, $args);
    }

    /**
     * Emit an event and merge listener responses back into the criteria array.
     *
     * @param array<string, mixed> $criteria
     * @return array<string, mixed>
     */
    public function response(string $eventName, array $criteria): array
    {
        $override = $this->trigger($eventName, $criteria);

        if (is_array($override) && !empty($override)) {
            $originalCriteria = $criteria;

            foreach ($override as $resp) {
                if (is_array($resp) && !empty($resp)) {
                    $keysDiff = array_diff_key($originalCriteria, $resp);

                    foreach ($keysDiff as $diffKey => $diffValue) {
                        if (!isset($resp[$diffKey])) {
                            unset($criteria[$diffKey]);
                        }
                    }

                    /** @var mixed $respValue */
                    foreach ($resp as $respKey => $respValue) {
                        $respKeyStr = (string) $respKey;

                        if (str_starts_with($respKeyStr, '__')) {
                            $criteria[$respKeyStr] = $respValue;
                        }

                        if (isset($originalCriteria[$respKeyStr]) && $originalCriteria[$respKeyStr] != $respValue) {
                            $criteria[$respKeyStr] = $respValue;
                        }
                    }
                }
            }
        }

        return $criteria;
    }

    /**
     * Remove all listeners for a specific event.
     */
    public function unbind(string $eventName): void
    {
        $this->adapter->unbind($eventName);
    }

    /**
     * Remove every listener for every event, releasing all closures from memory.
     */
    public function unbindAll(): void
    {
        $this->adapter->unbindAll();
    }

    /**
     * Check whether any listeners are registered for an event.
     */
    public function hasListeners(string $eventName): bool
    {
        return $this->adapter->hasListeners($eventName);
    }

    /**
     * Register a callback to run during PHP shutdown.
     */
    public function registerShutdownEvent(callable $callback, mixed ...$args): bool
    {
        $this->callbacks[] = array_values([$callback, ...$args]);

        return true;
    }

    /**
     * Execute all registered shutdown callbacks, then clear them.
     */
    public function callRegisteredShutdown(): void
    {
        foreach ($this->callbacks as $arguments) {
            /** @var callable $callback */
            $callback = array_shift($arguments);
            call_user_func_array($callback, $arguments);
        }

        $this->callbacks = [];
    }

    /**
     * Return the underlying adapter (for testing/debugging).
     */
    public function getAdapter(): LaravelEvent
    {
        return $this->adapter;
    }
}