<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements\Concerns;

use Arcanedev\Html\Elements\HtmlElement;
use Arcanedev\Html\Entities\ChildrenCollection;
use Closure;

/**
 * Trait     HasChildElements
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasChildElements
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The element's children.
     */
    protected ChildrenCollection $children;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the children elements.
     */
    public function getChildren(): ChildrenCollection
    {
        return $this->children;
    }

    /**
     * Set the children elements.
     *
     * @return $this
     */
    public function setChildren(ChildrenCollection $children): static
    {
        $this->children = $children;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Init the children elements.
     *
     * @return $this
     */
    public function initChildren(): static
    {
        return $this->setChildren(new ChildrenCollection());
    }

    /**
     * Alias for `addChild`.
     *
     * @return $this
     */
    public function children(mixed $children, Closure|array|null $mapper = null): static
    {
        return $this->addChild($children, $mapper);
    }

    /**
     * Add a child element to the parent.
     *
     * @return $this
     */
    public function addChild(mixed $child, Closure|array|null $mapper = null): static
    {
        if ($child === null) {
            return $this;
        }

        return tap(clone $this, function (HtmlElement $elt) use ($child, $mapper): void {
            $elt->setChildren(
                $elt->getChildren()->merge(ChildrenCollection::parse($child, $mapper))
            );
        });
    }

    /**
     * Replace all children with an array of elements.
     *
     * @return $this
     */
    public function setNewChildren(mixed $children, Closure|array|null $mapper = null): static
    {
        return tap(clone $this)
            ->initChildren()
            ->addChild($children, $mapper);
    }

    /**
     * Alias for `prependChildren`.
     *
     * @return $this
     */
    public function prependChild(mixed $children, Closure|array|null $mapper = null): static
    {
        return $this->prependChildren($children, $mapper);
    }

    /**
     * Prepend children elements.
     *
     * @return $this
     */
    public function prependChildren(mixed $children, Closure|array|null $mapper = null): static
    {
        return tap(clone $this, function (HtmlElement $elt) use ($children, $mapper): void {
            $elt->getChildren()
                ->prepend(ChildrenCollection::parse($children, $mapper));
        });
    }
}
