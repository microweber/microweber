<?php

namespace Goodby\CSV\Import\Standard;

use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException;
use Goodby\CSV\Import\Standard\Exception\StrictViolationException;

/**
 * standard interpreter
 *
 */
class Interpreter implements InterpreterInterface
{
    /**
     * @var array
     */
    private $observers = array();

    /**
     * @var int
     */
    private $rowConsistency = null;

    /**
     * @var bool
     */
    private $strict = true;

    /**
     * interpret line
     *
     * @param $line
     * @return void
     * @throws \Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException
     */
    public function interpret($line)
    {
        $this->checkRowConsistency($line);

        if (!is_array($line)) {
            throw new InvalidLexicalException('line is must be array');
        }

        $this->notify($line);
    }

    public function unstrict()
    {
        $this->strict = false;
    }

    /**
     * add observer
     *
     * @param callable $observer
     */
    public function addObserver($observer)
    {
        $this->checkCallable($observer);

        $this->observers[] = $observer;
    }

    /**
     * notify to observers
     *
     * @param $line
     */
    private function notify($line)
    {
        $observers = $this->observers;

        foreach ($observers as $observer) {
            $this->delegate($observer, $line);
        }
    }

    /**
     * delegate to observer
     *
     * @param $observer
     * @param $line
     */
    private function delegate($observer, $line)
    {
        call_user_func($observer, $line);
    }

    /**
     * check observer is callable
     *
     * @param $observer
     * @throws \InvalidArgumentException
     */
    private function checkCallable($observer)
    {
        if (!is_callable($observer)) {
            throw new \InvalidArgumentException('observer must be callable');
        }
    }

    private function checkRowConsistency($line)
    {
        if (!$this->strict) {
            return;
        }

        $current = count($line);

        if ($this->rowConsistency === null) {
            $this->rowConsistency = $current;
        }

        if ($current !== $this->rowConsistency) {
            throw new StrictViolationException(sprintf('Column size should be %u, but %u columns given', $this->rowConsistency, $current));
        }

        $this->rowConsistency = $current;
    }
}
