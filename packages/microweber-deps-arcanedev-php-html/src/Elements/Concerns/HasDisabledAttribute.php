<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

/**
 * Trait     HasDisabledAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Arcanedev\Html\Elements\Concerns\HasAttributes
 */
trait HasDisabledAttribute
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the disabled attribute.
     *
     * @return $this
     */
    public function disabled(bool $disabled = true): static
    {
        return $disabled
            ? $this->attribute('disabled', 'disabled')
            : $this->forgetAttribute('disabled');
    }
}
