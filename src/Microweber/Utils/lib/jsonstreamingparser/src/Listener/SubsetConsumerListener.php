<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

abstract class SubsetConsumerListener implements ListenerInterface
{
    protected $keyValueStack;

    /**
     * @var string|int|null
     */
    protected $key;

    public function startDocument(): void
    {
        $this->keyValueStack = [];
    }

    public function endDocument(): void
    {
    }

    public function startObject(): void
    {
        $this->keyValueStack[] = null === $this->key ? [[]] : [$this->key => []];
        $this->key = null;
    }

    public function endObject(): void
    {
        $keyValue = array_pop($this->keyValueStack);
        $obj = reset($keyValue);
        $this->key = key($keyValue);
        $hasBeenConsumed = $this->consume($obj);

        if (!empty($this->keyValueStack)) {
            $this->value($hasBeenConsumed ? '*consumed*' : $obj);
        }
    }

    public function startArray(): void
    {
        $this->startObject();
    }

    public function endArray(): void
    {
        $this->endObject();
    }

    public function key(string $key): void
    {
        $this->key = $key;
    }

    public function value($value): void
    {
        $keyValue = array_pop($this->keyValueStack);
        $objKey = key($keyValue);

        if ($this->key) {
            $keyValue[$objKey][$this->key] = $value;
        } else {
            $keyValue[$objKey][] = $value;
        }
        $this->keyValueStack[] = $keyValue;
    }

    public function whitespace(string $whitespace): void
    {
    }

    /**
     * @return bool if data was consumed and can be discarded
     */
    abstract protected function consume($data);
}
