<?php

declare(strict_types=1);

namespace Arcanedev\Html\Entities;

use Arcanedev\Html\Contracts\Renderable;
use Arcanedev\Html\Elements\HtmlElement;
use Arcanedev\Html\Exceptions\InvalidChildException;
use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\{Collection, HtmlString};

/**
 * Class     ChildrenCollection
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ChildrenCollection extends Collection implements Renderable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Parse the element's children.
     *
     * @return $this
     *
     * @throws InvalidChildException
     */
    public static function parse(mixed $children, Closure|array|null $mapper = null): static
    {
        return static::make($children)
            ->unless($mapper === null, fn(ChildrenCollection $items) => $items->map($mapper))
            ->each(function ($child): void {
                if ( ! static::isValidChild($child)) {
                    throw new InvalidChildException();
                }
            });
    }

    /**
     * Render the object as a string of HTML.
     */
    public function render(): HtmlString
    {
        return new HtmlString($this->toHtml());
    }

    /**
     * Get content as a string of HTML.
     */
    public function toHtml(): string
    {
        $mapper = function ($child): string {
            if ($child === null) {
                return '';
            }

            if ($child instanceof Htmlable) {
                return $child->toHtml();
            }

            if (is_string($child) || is_numeric($child)) {
                return (string) $child;
            }

            throw new InvalidChildException();
        };

        return $this->map($mapper)->implode('');
    }

    /**
     * Check if valid child.
     */
    protected static function isValidChild(mixed $child): bool
    {
        return $child instanceof Htmlable
            || is_string($child)
            || is_numeric($child)
            || $child === null;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Results array of items from Collection or Arrayable.
     */
    protected function getArrayableItems(mixed $items): array
    {
        if ($items instanceof HtmlElement || $items instanceof HtmlString) {
            return [$items];
        }

        return parent::getArrayableItems($items);
    }
}
