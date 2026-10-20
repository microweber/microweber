<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Illuminate\Support\Arr;

/**
 * Class     Dl
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Dl extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'dl';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add a term item.
     *
     * @return $this
     */
    public function dt(mixed $value, array $attributes = []): static
    {
        return $this->addChild(
            $this->makeTerm($value, $attributes)
        );
    }

    /**
     * Add a definition item.
     *
     * @return $this
     */
    public function dd(mixed $value, array $attributes = []): static
    {
        return $this->addChild(
            $this->makeDefinition($value, $attributes)
        );
    }

    /**
     * Add list items.
     *
     * @return $this
     */
    public function items(iterable $items, array $attributes = []): static
    {
        $dtAttributes = Arr::pull($attributes, 'dt', []);
        $ddAttributes = Arr::pull($attributes, 'dd', []);
        $dlItems      = [];

        foreach ($items as $term => $definitions) {
            // DT
            $dlItems[] = $this->makeTerm($term, $attributes)
                ->attributes($dtAttributes);
            // DD
            foreach (Arr::wrap($definitions) as $definition) {
                $dlItems[] = $this->makeDefinition($definition, $attributes)
                    ->attributes($ddAttributes);
            }
        }

        return $this->children($dlItems);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a term item.
     */
    protected function makeTerm(mixed $value, array $attributes = []): HtmlElement
    {
        return static::withTag('dt')->attributes($attributes)->html($value);
    }

    /**
     * Make a definition item.
     */
    protected function makeDefinition(mixed $value, array $attributes = []): HtmlElement
    {
        return static::withTag('dd')->attributes($attributes)->html($value);
    }
}
