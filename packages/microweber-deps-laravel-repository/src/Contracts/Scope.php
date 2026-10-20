<?php

namespace Torann\LaravelRepository\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Scope
{
    /**
     * @param array $arguments
     *
     * @return static
     */
    public static function make(...$arguments): static;

    /**
     * Determine if the scope be skipped
     *
     * @return bool
     */
    public function shouldSkip(): bool;

    /**
     * Apply the scope
     *
     * @param Builder    $builder
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply(Builder $builder, Repository $repository): Builder;
}
