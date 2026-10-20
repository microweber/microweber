<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

use Closure;

/**
 * Trait     HasConditionalMethods
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasConditionalMethods
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The supported conditions.
     */
    protected array $supportedConditions = [
        'If',
        'Unless',
        'IfNotNull',
    ];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Conditionally transform the element.
     * Note that since elements are immutable, you'll need to return a new instance from the callback.
     *
     * @return $this|mixed
     */
    public function if(bool $condition, Closure $callback): mixed
    {
        return $condition ? $callback($this) : $this;
    }

    /**
     * Conditionally transform the element.
     * Note that since elements are immutable, you'll need to return a new instance from the callback.
     *
     * @return $this|mixed
     */
    public function unless(bool $condition, Closure $callback): mixed
    {
        return $this->if( ! $condition, $callback);
    }

    /**
     * Conditionally transform the element.
     * Note that since elements are immutable, you'll need to return a new instance from the callback.
     *
     * @return $this|mixed
     */
    public function ifNotNull(mixed $value, Closure $callback): mixed
    {
        return $this->if($value !== null, $callback);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Call the if condition.
     *
     * @return $this|mixed
     */
    protected function callConditionalMethod(string $conditions, string $method, array $arguments): mixed
    {
        $value    = array_shift($arguments);
        $callback = fn(): self => $this->{$method}(...$arguments);

        return match ($conditions) {
            'If' => $this->if((bool) $value, $callback),
            'Unless' => $this->unless((bool) $value, $callback),
            'IfNotNull' => $this->ifNotNull($value, $callback),
            default => $this,
        };
    }
}
