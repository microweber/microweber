<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

/**
 * Trait     HasNameAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Arcanedev\Html\Elements\Concerns\HasAttributes
 */
trait HasNameAttribute
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the name attribute.
     *
     * @return $this
     */
    public function name(string $name): static
    {
        return $this->attribute('name', $name);
    }
}
