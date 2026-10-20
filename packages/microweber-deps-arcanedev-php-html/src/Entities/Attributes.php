<?php

declare(strict_types=1);

namespace Arcanedev\Html\Entities;

use Arcanedev\Html\Entities\Attributes\AbstractAttribute;
use Arcanedev\Html\Entities\Attributes\ClassAttribute;
use Arcanedev\Html\Entities\Attributes\MiscAttribute;
use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class     Attributes
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Attributes implements ArrayAccess, Arrayable
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected array $items;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Attributes constructor.
     */
    public function __construct(array $items = [])
    {
        $this->items = [];
        $this->setMany($items);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make the attribute instance.
     */
    public static function make(array $items = []): static
    {
        return new static($items);
    }

    /**
     * Set an attribute.
     *
     * @return $this
     */
    public function set(string $name, mixed $value = null): static
    {
        $attribute = static::makeAttribute($name, $value);

        $this->offsetSet($attribute->name(), $attribute);

        return $this;
    }

    /**
     * Get an attribute.
     *
     * @return $this|mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->offsetExists($key)
            ? $this->offsetGet($key)
            : value($default);
    }

    /**
     * Forget one or multiple attributes
     *
     * @return $this
     */
    public function forget(array|string $keys): static
    {
        foreach ((array) $keys as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param string|array<string> ...$keys
     *
     * @return bool
     */
    public function has(...$keys): bool
    {
        foreach ($keys as $value) {
            if ( ! $this->offsetExists($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set multiple attributes.
     *
     * @return $this
     */
    public function setMany(iterable $attributes): static
    {
        foreach ($attributes as $attribute => $value) {
            if (is_int($attribute)) {
                $attribute = $value;
                $value     = null;
            }

            $this->set($attribute, $value);
        }

        return $this;
    }

    /**
     * Add a class.
     *
     * @return $this
     */
    public function addClass(iterable|string $class): static
    {
        return $this->set('class', $class);
    }

    /**
     * Render the attributes.
     */
    public function render(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        return implode(' ', array_map(
            fn(AbstractAttribute $attribute) => $attribute->render(),
            $this->items
        ));
    }

    /**
     * Convert the attributes to array.
     */
    public function toArray(): array
    {
        return array_map(
            fn(AbstractAttribute $attribute) => $attribute->value(),
            $this->items
        );
    }

    /**
     * Check if the container is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Check if the container is not empty.
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Get the class attribute.
     */
    public function classList(): ?ClassAttribute
    {
        return $this->get('class');
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /** {@inheritDoc} */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /** {@inheritDoc} */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /** {@inheritDoc} */
    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    /** {@inheritDoc} */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Make a new attribute.
     */
    protected static function makeAttribute(string $name, mixed $value): ClassAttribute|MiscAttribute
    {
        return match ($name) {
            'class' => new ClassAttribute($value),
            default => new MiscAttribute($name, $value),
        };
    }
}
