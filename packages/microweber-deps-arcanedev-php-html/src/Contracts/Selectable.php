<?php

declare(strict_types=1);

namespace Arcanedev\Html\Contracts;

/**
 * Interface  Selectable
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Selectable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the selected attribute.
     */
    public function selected(): static;

    /**
     * Add the selected if it fulfill the condition.
     */
    public function selectedIf(bool $condition): static;

    /**
     * Remove the selected attribute.
     */
    public function unselected(): static;
}
