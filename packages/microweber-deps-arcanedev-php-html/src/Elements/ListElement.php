<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

/**
 * Class     ListElement
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class ListElement extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make an item.
     */
    abstract protected function makeItem(mixed $value, array $attributes): HtmlElement;

    /**
     * Add an item.
     *
     * @return $this
     */
    public function item(mixed $value, array $attributes = []): static
    {
        return $this->addChild($value, fn($value) => $this->makeItem($value, $attributes));
    }

    /**
     * Add multiple items.
     *
     * @param  iterable  $items
     * @param  array     $attributes
     *
     * @return $this
     */
    public function items(iterable $items, array $attributes = []): static
    {
        return $this->children($items, fn($value) => $this->makeItem(
            is_array($value) ? static::make()->items($value) : $value, // Create nested items if the value is array
            $attributes
        ));
    }
}
