<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

/**
 * Class     Ul
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Ul extends ListElement
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'ul';

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make an item.
     */
    protected function makeItem(mixed $value, array $attributes): HtmlElement
    {
        return static::withTag('li')
            ->attributes($attributes)
            ->html($value);
    }
}
