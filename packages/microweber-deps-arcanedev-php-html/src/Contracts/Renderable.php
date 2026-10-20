<?php

declare(strict_types=1);

namespace Arcanedev\Html\Contracts;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

/**
 * Interface  Renderable
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Renderable extends Htmlable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Render the object as a string of HTML.
     */
    public function render(): HtmlString;
}
