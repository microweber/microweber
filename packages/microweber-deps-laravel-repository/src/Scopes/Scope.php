<?php

namespace Torann\LaravelRepository\Scopes;

abstract class Scope implements \Torann\LaravelRepository\Contracts\Scope
{
    /**
     * {@inheritDoc}
     */
    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }
}
