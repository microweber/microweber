<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

/**
 * This basic implementation of a listener simply constructs an in-memory
 * representation of the JSON document, which is a little silly since the whole
 * point of a streaming parser is to avoid doing just that. However, it can
 * serve as a starting point for more complex listeners, and illustrates some
 * useful concepts for working with a streaming-style parser.
 */
class InMemoryListener extends IdleListener
{
    protected $result;

    /**
     * @var array
     */
    protected $stack;

    /**
     * @var string[]
     */
    protected $keys;

    public function getJson()
    {
        return $this->result;
    }

    public function startDocument(): void
    {
        $this->stack = [];
        $this->keys = [];
    }

    public function startObject(): void
    {
        $this->startComplexValue('object');
    }

    public function endObject(): void
    {
        $this->endComplexValue();
    }

    public function startArray(): void
    {
        $this->startComplexValue('array');
    }

    public function endArray(): void
    {
        $this->endComplexValue();
    }

    public function key(string $key): void
    {
        $this->keys[] = $key;
    }

    public function value($value): void
    {
        $this->insertValue($value);
    }

    protected function startComplexValue($type): void
    {
        // We keep a stack of complex values (i.e. arrays and objects) as we build them,
        // tagged with the type that they are so we know how to add new values.
        $currentItem = ['type' => $type, 'value' => []];
        $this->stack[] = $currentItem;
    }

    protected function endComplexValue(): void
    {
        $obj = array_pop($this->stack);

        // If the value stack is now empty, we're done parsing the document, so we can
        // move the result into place so that getJson() can return it. Otherwise, we
        // associate the value
        if (empty($this->stack)) {
            $this->result = $obj['value'];
        } else {
            $this->insertValue($obj['value']);
        }
    }

    // Inserts the given value into the top value on the stack in the appropriate way,
    // based on whether that value is an array or an object.
    protected function insertValue($value): void
    {
        // Grab the top item from the stack that we're currently parsing.
        $currentItem = array_pop($this->stack);

        // Examine the current item, and then:
        //   - if it's an object, associate the newly-parsed value with the most recent key
        //   - if it's an array, push the newly-parsed value to the array
        if ('object' === $currentItem['type']) {
            $currentItem['value'][array_pop($this->keys)] = $value;
        } else {
            $currentItem['value'][] = $value;
        }

        // Replace the current item on the stack.
        $this->stack[] = $currentItem;
    }
}
