<?php

declare(strict_types=1);

namespace Arcanedev\Html\Entities\Attributes;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\{
    Arr,
    Collection};

/**
 * Class     ClassAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ClassAttribute extends AbstractAttribute implements Countable
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The attribute's name.
     */
    protected string $name = 'class';

    /**
     * The CSS classes.
     *
     * @var array<int, string>
     */
    protected array $classes = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * ClassAttribute constructor.
     */
    public function __construct(mixed $value = null)
    {
        $this->setValue($value);
    }

    /**
     * Render the attribute.
     *
     * @see render
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Make a new instance.
     *
     * @return $this
     */
    public static function make(mixed $value = null): static
    {
        return new static($value);
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the attribute's value.
     */
    public function value(): string
    {
        return implode(' ', $this->all());
    }

    /**
     * Get the attribute's value.
     *
     * @return $this
     */
    public function setValue(mixed $value): static
    {
        if ($value !== null) {
            $this->classes = static::parseClasses($value);
        }

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get all the classes.
     */
    public function all(): array
    {
        return $this->classes;
    }

    /**
     * Push the given class.
     *
     * @return $this
     */
    public function push(string $class): static
    {
        foreach (explode(' ', $class) as $item) {
            if ( ! $this->has($item)) {
                $this->classes[] = $item;
            }
        }

        return $this;
    }

    /**
     * Remove the given class.
     *
     * @return $this
     */
    public function remove(string $class): static
    {
        if ( ! $this->has($class)) {
            return $this;
        }

        $class = trim($class);

        return $this->setValue(
            array_diff($this->all(), [$class])
        );
    }

    /**
     * Toggle the given class.
     */
    public function toggle(string $class): static
    {
        return $this->has($class)
            ? $this->remove($class)
            : $this->push($class);
    }

    /**
     * Check if contains the given class (alias).
     *
     * @see  has
     */
    public function contains(string $class): bool
    {
        return $this->has($class);
    }

    /**
     * Check if contains the given class.
     */
    public function has(string $class): bool
    {
        return in_array(trim($class), $this->all());
    }

    /**
     * Replaces an existing class with a new class.
     *
     * @return $this
     */
    public function replace(string $oldClass, string $newClass): static
    {
        if ($this->has($oldClass)) {
            $this->remove($oldClass)->push($newClass);
        }

        return $this;
    }

    /**
     * Count the classes.
     */
    public function count(): int
    {
        return count($this->all());
    }

    /**
     * Check if is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    /**
     * Check if is not empty.
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Render the attribute.
     */
    public function render(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        return $this->name . '="' . e($this->value(), false) . '"';
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Parse the given value.
     */
    private static function parseClasses(mixed $classes): array
    {
        if ($classes instanceof Arrayable) {
            $classes = $classes->toArray();
        }

        if (is_string($classes)) {
            $classes = explode(' ', $classes);
        }

        if (Arr::isAssoc($classes)) {
            $classes = Collection::make($classes)
                ->transform(fn($value, $key) => is_numeric($key) ? $value : ($value ? $key : false))
                ->filter()
                ->values()
                ->toArray();
        }

        return array_unique($classes);
    }
}
