<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Arcanedev\Html\Elements\Concerns\HasAutofocusAttribute;
use Arcanedev\Html\Elements\Concerns\HasDisabledAttribute;
use Arcanedev\Html\Elements\Concerns\HasMinMaxLengthAttributes;
use Arcanedev\Html\Elements\Concerns\HasNameAttribute;
use Arcanedev\Html\Elements\Concerns\HasPlaceholderAttribute;
use Arcanedev\Html\Elements\Concerns\HasReadonlyAttribute;
use Arcanedev\Html\Elements\Concerns\HasRequiredAttribute;

/**
 * Class     Textarea
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Textarea extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasAutofocusAttribute;
    use HasDisabledAttribute;
    use HasMinMaxLengthAttributes;
    use HasNameAttribute;
    use HasPlaceholderAttribute;
    use HasReadonlyAttribute;
    use HasRequiredAttribute;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'textarea';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set the textarea value.
     *
     * @return $this
     */
    public function value(?string $value): static
    {
        return $this->html($value);
    }

    /**
     * Set the textarea cols & rows sizes.
     *
     * @return $this
     */
    public function size(string $size): static
    {
        list($cols, $rows) = explode('x', $size);

        return $this->attribute('cols', $cols)->attribute('rows', $rows);
    }
}
