<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

/**
 * Trait     HasReadonlyAttribute
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Arcanedev\Html\Elements\Concerns\HasAttributes
 */
trait HasReadonlyAttribute
{
    /**
     * Add the readonly attribute.
     *
     * @return $this
     */
    public function isReadonly(bool $readonly = true): static
    {
        return $readonly
            ? $this->attribute('readonly')
            : $this->forgetAttribute('readonly');
    }
}
