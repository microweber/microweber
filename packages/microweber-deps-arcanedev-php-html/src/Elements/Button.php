<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Arcanedev\Html\Elements\Concerns\HasDisabledAttribute;
use Arcanedev\Html\Elements\Concerns\HasNameAttribute;
use Arcanedev\Html\Elements\Concerns\HasTypeAttribute;
use Arcanedev\Html\Elements\Concerns\HasValueAttribute;

/**
 * Class     Button
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Button extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasDisabledAttribute;
    use HasNameAttribute;
    use HasTypeAttribute;
    use HasValueAttribute;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'button';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set as submit button.
     *
     * @return $this
     */
    public function submit(): static
    {
        return $this->type('submit');
    }

    /**
     * Set as reset button.
     *
     * @return $this
     */
    public function reset(): static
    {
        return $this->type('reset');
    }
}
