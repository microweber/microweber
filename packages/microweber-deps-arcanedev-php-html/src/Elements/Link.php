<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

/**
 * Class     Link
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Link extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'link';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set the href url.
     *
     * @return $this
     */
    public function href(string $href): static
    {
        return $this->attribute('href', $href);
    }

    /**
     * Set the rel value.
     * @link  https://developer.mozilla.org/en-US/docs/Web/HTML/Link_types
     *
     * @return $this
     */
    public function rel(string $value): static
    {
        return $this->attribute('rel', $value);
    }

    /**
     * Set the rel as stylesheet.
     *
     * @return $this
     */
    public function stylesheet(string $href): static
    {
        return $this->rel('stylesheet')->href($href);
    }

    /**
     * Set the rel as icon.
     *
     * @return $this
     */
    public function icon(string $href): static
    {
        return $this->rel('icon')->href($href);
    }
}
