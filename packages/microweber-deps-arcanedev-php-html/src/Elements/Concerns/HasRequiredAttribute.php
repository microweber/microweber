<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

/**
 * Trait     HasRequiredAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Arcanedev\Html\Elements\Concerns\HasAttributes
 */
trait HasRequiredAttribute
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the required attribute.
     *
     * @return $this
     */
    public function required(bool $required = true): static
    {
        return $required
            ? $this->attribute('required')
            : $this->forgetAttribute('required');
    }
}
