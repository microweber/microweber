<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

use Arcanedev\Html\Elements\HtmlElement;
use Arcanedev\Html\Entities\Attributes;

/**
 * Trait     HasAttributes
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasAttributes
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The element's attributes.
     */
    protected Attributes $attributes;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the element attributes.
     */
    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Reset the attributes.
     *
     * @return $this
     */
    public function initAttributes(): static
    {
        $this->attributes = new Attributes();

        return $this;
    }

    /**
     * Set an attribute.
     *
     * @return $this
     */
    public function attribute(string $name, mixed $value = null): static
    {
        return tap(clone $this, function (HtmlElement $elt) use ($name, $value): void {
            $elt->getAttributes()->set($name, $value);
        });
    }

    /**
     * Set the attributes.
     *
     * @return $this
     */
    public function attributes(iterable $attributes): static
    {
        return tap(clone $this, function (HtmlElement $elt) use ($attributes): void {
            $elt->getAttributes()->setMany($attributes);
        });
    }

    /**
     * Forget attribute.
     *
     * @return $this
     */
    public function forgetAttribute(string $name): static
    {
        if ( ! $this->hasAttribute($name)) {
            return $this;
        }

        return tap(clone $this, function (self $elt) use ($name): void {
            $elt->getAttributes()->forget($name);
        });
    }

    /**
     * Get an attribute.
     *
     * @return \Arcanedev\Html\Entities\Attributes\MiscAttribute|mixed
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->getAttributes()->get($name, $default);
    }

    /**
     * Check if attribute exists.
     */
    public function hasAttribute(string $attribute): bool
    {
        return $this->getAttributes()->has($attribute);
    }

    /**
     * Get the attribute's value.
     */
    protected function getAttributeValue(string $name): ?string
    {
        if ( ! $this->hasAttribute($name)) {
            return null;
        }

        return $this->getAttribute($name)->value();
    }
}
