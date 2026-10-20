<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

/**
 * Trait     HasMinMaxLengthAttributes
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Arcanedev\Html\Elements\Concerns\HasAttributes
 */
trait HasMinMaxLengthAttributes
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the `minlength` attribute.
     *
     * @return $this
     */
    public function minlength(int $length): static
    {
        return $this->attribute('minlength', $length);
    }

    /**
     * Add the `maxlength` attribute.
     *
     * @return $this
     */
    public function maxlength(int $length): static
    {
        return $this->attribute('maxlength', $length);
    }
}
