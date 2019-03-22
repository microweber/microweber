<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

/**
 * This implementation of a listener constructs an in-memory
 * representation of the JSON document, this is useful to repair
 * cut json documents (has unexpected end of the files).
 */
class CorruptedJsonListener implements ListenerInterface
{
    protected $json;

    protected $stack;
    /**
     * @var array
     */
    protected $keys;

    // Level is required so we know how nested we are.
    protected $level;

    public function getJson()
    {
        return $this->json;
    }

    public function startDocument(): void
    {
        $this->stack = [];
        $this->level = 0;
        // Key is an array so that we can can remember keys per level to avoid
        // it being reset when processing child keys.
        $this->keys = [];
    }

    public function endDocument(): void
    {
    }

    public function startArray(): void
    {
        $this->startObject();
    }

    public function startObject(): void
    {
        ++$this->level;
        $this->stack[] = [];
    }

    public function endArray(): void
    {
        $this->endObject();
    }

    public function endObject(): void
    {
        --$this->level;
        $obj = array_pop($this->stack);
        if (empty($this->stack)) {
            // doc is DONE!
            $this->json = $obj;
        } else {
            $this->value($obj);
        }
    }

    /**
     * Value may be a string, integer, boolean, null.
     */
    public function value($value): void
    {
        $obj = array_pop($this->stack);
        if (!empty($this->keys[$this->level])) {
            $obj[$this->keys[$this->level]] = $value;
            $this->keys[$this->level] = null;
        } else {
            $obj[] = $value;
        }
        $this->stack[] = $obj;
    }

    public function key(string $key): void
    {
        $this->keys[$this->level] = $key;
    }

    public function whitespace(string $whitespace): void
    {
        // do nothing
    }

    /**
     * Forcefully finish the document, end all objects and arrays
     * and set final object to the json property.
     */
    public function forceEndDocument(): void
    {
        if (empty($this->stack)) {
            return;
        }

        $key = $this->keys;
        for ($i = $this->level - 1; $i > 0; --$i) {
            $value = array_pop($this->stack);
            //value
            $obj = array_pop($this->stack);
            if (!empty($key[$i])) {
                $obj[$key[$i]] = $value;
                $key[$i] = null;
            } else {
                $obj[] = $value;
            }
            if ($i > 1) {
                $this->stack[] = $obj;
            } else {
                $this->json = $obj;
            }
        }
    }

    /**
     * Return stack of keys before force ending documents.
     */
    public function getKeys(): array
    {
        return $this->keys;
    }
}
