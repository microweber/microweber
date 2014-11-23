<?php

namespace Goodby\CSV\Export\Standard\Collection;

use Iterator;
use IteratorAggregate;

class CallbackCollection implements Iterator
{
    private $callable;
    private $data;

    public function __construct($data, $callable)
    {
        $this->callable = $callable;

        if (!is_callable($callable)) {
            throw new \InvalidArgumentException('the second argument must be callable');
        }

        if (is_array($data)) {
            $ao = new \ArrayObject($data);
            $this->data = $ao->getIterator();
        } elseif ($data instanceof Iterator) {
            $this->data = $data;
        } elseif ($data instanceof IteratorAggregate) {
            $this->data = $data->getIterator();
        } else {
            throw new \InvalidArgumentException('data must be an array or an Iterator/IteratorAggregate');
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return call_user_func($this->callable, $this->data->current());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->data->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->data->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->data->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->data->rewind();
    }
}
