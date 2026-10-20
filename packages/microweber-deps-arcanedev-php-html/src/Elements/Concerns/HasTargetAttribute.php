<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

/**
 * Trait     HasTargetAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasTargetAttribute
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the target attribute.
     *
     * @return $this
     */
    public function target(string $target): static
    {
        return $this->attribute('target', $target);
    }
}
