<?php

namespace MicroweberPackages\Product;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

class CartesianProduct implements IteratorAggregate, Countable
{
    /**
     * @var array
     */
    private $set = [];

    /**
     * @var bool
     */
    private $isRecursiveStep = false;

    /**
     * @var int
     */
    private $count;

    /**
     * CartesianProduct constructor.
     *
     * @param array $set - A multidimensionnal array.
     */
    public function __construct(array $set)
    {
        $this->set = $set;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        if ([] === $this->set) {
            if (true === $this->isRecursiveStep) {
                yield [];
            }

            return;
        }

        $set = $this->set;
        $keys = \array_keys($set);
        $last = \end($keys);
        $subset = \array_pop($set);
        $this->validate($subset, $last);
        foreach (self::subset($set) as $product) {
            foreach ($subset as $value) {
                yield $product + [$last => ($value instanceof \Closure ? $value($product) : $value)];
            }
        }
    }

    /**
     * @param $subset
     * @param $key
     */
    private function validate($subset, $key)
    {
        // Validate array subset
        if (\is_array($subset) && !empty($subset)) {
            return;
        }

        // Validate iterator subset
        if ($subset instanceof Traversable && $subset instanceof Countable && \count($subset) > 0) {
            return;
        }

        throw new InvalidArgumentException(\sprintf('Key "%s" should return a non-empty iterable', $key));
    }

    /**
     * @param  array $subset
     * @return CartesianProduct
     */
    private static function subset(array $subset)
    {
        $product = new self($subset);
        $product->isRecursiveStep = true;
        return $product;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return \iterator_to_array($this);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        if (null === $this->count) {
            $this->count = (int) \array_product(
                \array_map(
                    function ($subset, $key) {
                        $this->validate($subset, $key);
                        return \count($subset);
                    },
                    $this->set,
                    \array_keys($this->set)
                )
            );
        }
        return $this->count;
    }
}
