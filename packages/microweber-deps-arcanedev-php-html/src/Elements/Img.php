<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

/**
 * Class     Img
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Img extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'img';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set the src attribute.
     *
     * @return $this
     */
    public function src(string $src): static
    {
        return $this->attribute('src', $src);
    }

    /**
     * Set the alt attribute.
     *
     * @return $this
     */
    public function alt(string $alt): static
    {
        return $this->attribute('alt', $alt);
    }
}
