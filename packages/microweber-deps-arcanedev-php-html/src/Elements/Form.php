<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Arcanedev\Html\Elements\Concerns\HasTargetAttribute;

/**
 * Class     Form
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Form extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasTargetAttribute;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'form';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the action attribute.
     *
     * @return $this
     */
    public function action(?string $action): static
    {
        return $this->attribute('action', $action);
    }

    /**
     * Add the method attribute.
     *
     * @return $this
     */
    public function method(string $method): static
    {
        $method = mb_strtoupper($method);

        return $this->attribute('method', $method === 'GET' ? 'GET' : 'POST');
    }

    /**
     * Add the novalidate attribute.
     *
     * @return $this
     */
    public function novalidate(bool $novalidate = true): static
    {
        return $novalidate
            ? $this->attribute('novalidate')
            : $this->forgetAttribute('novalidate');
    }

    /**
     * Add the enctype attribute for files.
     *
     * @return $this
     */
    public function acceptsFiles(): static
    {
        return $this->attribute('enctype', 'multipart/form-data');
    }
}
