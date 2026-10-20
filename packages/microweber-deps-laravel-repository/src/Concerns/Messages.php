<?php

namespace Torann\LaravelRepository\Concerns;

use Illuminate\Support\MessageBag;

trait Messages
{
    protected MessageBag|null $message_bag = null;

    /**
     * {@inheritDoc}
     */
    public function getMessageBag(): MessageBag
    {
        if ($this->message_bag === null) {
            $this->message_bag = new MessageBag;
        }

        return $this->message_bag;
    }

    /**
     * {@inheritDoc}
     */
    public function addMessage(string $message, string $key = 'message'): static
    {
        $this->getMessageBag()->add($key, $message);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasMessage(string $key = 'message'): bool
    {
        return $this->getMessageBag()->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(string|null $key = null, string|null $format = null, string $default = ''): string
    {
        return $this->getMessageBag()->first($key, $format) ?: $default;
    }

    /**
     * {@inheritDoc}
     */
    public function addError(string $message): static
    {
        $this->addMessage($message, 'error');

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasErrors(): bool
    {
        return $this->getMessageBag()->has('error');
    }

    /**
     * {@inheritDoc}
     */
    public function getErrors(string|null $format = null): array
    {
        return $this->getMessageBag()->get('error', $format);
    }

    /**
     * {@inheritDoc}
     */
    public function getErrorMessage(string $default = ''): string
    {
        return $this->getMessage('error') ?: $default;
    }
}
