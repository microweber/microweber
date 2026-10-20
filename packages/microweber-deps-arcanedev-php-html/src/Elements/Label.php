<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

/**
 * Class     Label
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Label extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'label';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set the for attribute.
     *
     * @return $this
     */
    public function for(string $for): static
    {
        return $this->attribute('for', $for);
    }
}
