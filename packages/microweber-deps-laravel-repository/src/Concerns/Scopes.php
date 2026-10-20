<?php

namespace Torann\LaravelRepository\Concerns;

use Closure;
use Torann\LaravelRepository\Contracts\Scope;

trait Scopes
{
    /**
     * Search and Ordering scopes.
     */
    protected array $global_scopes = [];

    /**
     * Global query scope.
     */
    protected array $scopeQuery = [];

    /**
     * @param array $scopes
     *
     * @return static
     */
    public function addGlobalScopes(array $scopes): static
    {
        foreach ($scopes as $name => $scope) {
            $this->addGlobalScope($name, $scope);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $scope
     *
     * @return static
     */
    public function addGlobalScope(string $name, string $scope): static
    {
        $this->global_scopes[$name] = $scope;

        return $this;
    }

    /**
     * @param array $names
     *
     * @return static
     */
    public function removeGlobalScope(...$names): static
    {
        foreach ($names as $name) {
            unset($this->global_scopes[$name]);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function resolveScope(string $name): string|null
    {
        $scope = $this->global_scopes[$name] ?? null;

        if ($scope === null || class_exists($scope) === false) {
            $scope = config("repositories.scopes.{$name}");
        }

        return class_exists($scope) ? $scope : null;
    }

    /**
     * Return query scope.
     *
     * @return array
     */
    public function getScopeQuery(): array
    {
        return $this->scopeQuery;
    }

    /**
     * Add query scope.
     *
     * @param Closure|Scope $scope
     * @param string|null   $key
     *
     * @return static
     */
    public function addScopeQuery(Closure|Scope $scope, string|null $key = null): static
    {
        if ($scope instanceof Scope && $scope->shouldSkip()) {
            return $this;
        }

        // Prevent dupes when using some scopes
        if ($key) {
            $this->scopeQuery[$key] = $scope;
        } else {
            $this->scopeQuery[] = $scope;
        }

        return $this;
    }

    /**
     * Apply scope in current Query
     *
     * @return static
     */
    protected function applyScope(): static
    {
        foreach ($this->scopeQuery as $callback) {
            if (is_callable($callback)) {
                $this->query = $callback($this->query);
            } elseif ($callback instanceof Scope) {
                $this->query = $callback->apply($this->query, $this);
            }
        }

        // Clear scopes
        $this->scopeQuery = [];

        return $this;
    }

    /**
     * Reset internal Query
     *
     * @return static
     */
    protected function scopeReset(): static
    {
        $this->scopeQuery = [];

        $this->newQuery();

        return $this;
    }
}
