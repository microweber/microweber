<?php

declare(strict_types=1);

namespace Arcanedev\Html\Entities\Attributes;

/**
 * Class     MiscAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MiscAttribute extends AbstractAttribute
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The attribute's value.
     *
     * @var mixed
     */
    protected mixed $value;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Attribute constructor.
     */
    public function __construct(string $name, mixed $value)
    {
        $this->name = $name;
        $this->setValue($value);
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the attribute's value.
     */
    public function value(): mixed
    {
        return $this->value;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Render the attribute.
     */
    public function render(): string
    {
        $value = $this->value();

        return ($value === null || $value === '')
            ? $this->name
            : $this->name . '="' . e($value, false) . '"';
    }

    /**
     * Set the value.
     *
     * @return $this
     */
    protected function setValue(mixed $value): static
    {
        $this->value = trim((string) $value);

        return $this;
    }
}
