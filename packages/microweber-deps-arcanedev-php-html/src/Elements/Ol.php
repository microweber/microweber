<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

/**
 * Class     Ol
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Ol extends ListElement
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'ol';

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make an item.
     */
    protected function makeItem(mixed $value, array $attributes): HtmlElement
    {
        return static::withTag('li')->attributes($attributes)->html($value);
    }
}
