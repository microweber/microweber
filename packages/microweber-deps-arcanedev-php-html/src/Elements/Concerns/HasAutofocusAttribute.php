<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

/**
 * Trait     HasAutofocusAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Arcanedev\Html\Elements\Concerns\HasAttributes
 */
trait HasAutofocusAttribute
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the autofocus attribute.
     *
     * @return $this
     */
    public function autofocus(bool $autofocus = true): static
    {
        return $autofocus
            ? $this->attribute('autofocus')
            : $this->forgetAttribute('autofocus');
    }

}
