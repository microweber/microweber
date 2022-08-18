<?php
namespace MicroweberPackages\Product\tests;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class CountableIterator
 * @internal
 */
final class CountableIterator implements IteratorAggregate, Countable
{

    private array $items = [];

    /**
     * CountableIterator constructor.
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }


    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }
}
