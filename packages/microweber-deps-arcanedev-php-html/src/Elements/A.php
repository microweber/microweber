<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Arcanedev\Html\Elements\Concerns\HasTargetAttribute;

/**
 * Class     A
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class A extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasTargetAttribute;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'a';

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
}
