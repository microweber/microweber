<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

/**
 * This listener is for parsing very simple JSON files that contain an array of objects.
 * For example [{"id":"1", "name":"foo"}, {"id","2","name":"bar"}]
 * This can be useful for seeding databases or importing massive amounts of data.
 * Please do note that is intended for single level simple objects.
 * To support nested objects you need to modify the code as suited to your purposes.
 */
class SimpleObjectQueueListener implements ListenerInterface
{
    public const TYPE_ARRAY = 1;
    public const TYPE_OBJECT = 2;

    /**
     * @var array will hold the current object being parsed as an associative array
     */
    protected $currentObject = [];

    /**
     * @var string|null will hold the current key used to feed
     */
    protected $currentKey;

    /**
     * @var callable this will be called to perform an action on the compiled object
     */
    protected $callback;

    /**
     * @var int which return type to provide
     */
    protected $returnType;

    /**
     * Initiate the listener for very simple objects that do not contain nested elements.
     * For example [{"id":"1", "name":"foo"}, {"id","2","name":"bar"}].
     *
     * @param callable $callback
     * @param int      $returnType one
     *
     * type to callback. Defaults to to associative array.<BR/>
     *     SimpleObjectQueueListener::TYPE_ARRAY will provide an associative array to the callback<BR/>
     *     SimpleObjectQueueListener::TYPE_OBJECT will privde an object to the callback
     */
    public function __construct(callable $callback = null, int $returnType = self::TYPE_ARRAY)
    {
        $this->returnType = $returnType;
        $this->callback = $callback;
    }

    public function startDocument(): void
    {
        $this->reset();
    }

    public function endDocument(): void
    {
        $this->reset();
    }

    public function startObject(): void
    {
        $this->reset();
    }

    public function endObject(): void
    {
        /*
         * Return the currently compiled object to the callback.
         */
        if (self::TYPE_ARRAY === $this->returnType) {
            \call_user_func($this->callback, $this->currentObject);
        } elseif (self::TYPE_OBJECT === $this->returnType) {
            \call_user_func($this->callback, (object) $this->currentObject);
        } else {
            throw new \Exception('Unsupported callback data type requested.');
        }
    }

    public function startArray(): void
    {
        /* we support an array of objects, not nested arrays. leave this alone */
    }

    public function endArray(): void
    {
        /* no need to support arrays */
    }

    public function key(string $key): void
    {
        $this->currentKey = $key;
    }

    public function value($value): void
    {
        $this->currentObject[$this->currentKey] = $value;
    }

    public function whitespace(string $whitespace): void
    {
    }

    /**
     * Reset all the values to default.
     */
    protected function reset(): void
    {
        $this->currentObject = [];
        $this->currentKey = null;
    }
}
