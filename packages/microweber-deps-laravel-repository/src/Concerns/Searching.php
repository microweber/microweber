<?php

namespace Torann\LaravelRepository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Torann\LaravelRepository\Contracts\Scope;

trait Searching
{
    /**
     * Searching operator. This might be different when using
     * a different database driver.
     */
    public static string $searchOperator = 'LIKE';

    /**
     * A set of keys used to perform range queries.
     */
    protected array $range_keys = [
        'lt', 'gt',
        'bt', 'ne',
    ];

    /**
     * Valid searchable columns
     */
    protected array $searchable = [];

    /**
     * Set searchable array.
     *
     * @param array|string $values
     * @param bool         $safe
     *
     * @return static
     */
    public function setSearchable(array|string $values, bool $safe = false): static
    {
        if (is_string($values)) {
            $values = [$values => $values];
        }

        foreach ($values as $key => $value) {
            if ($safe === true) {
                if (isset($this->searchable[$key]) || array_search($key, $this->searchable) !== false) {
                    continue;
                }
            }

            $this->searchable[$key] = $value;
        }

        return $this;
    }

    /**
     * Return searchable keys.
     *
     * @return array
     */
    public function getSearchableKeys(): array
    {
        $return = $this->getSearchable();

        return array_values(array_map(function ($value, $key) {
            return (is_array($value) || is_numeric($key) === false) ? $key : $value;
        }, $return, array_keys($return)));
    }

    /**
     * Return searchable array.
     *
     * @return array
     */
    public function getSearchable(): array
    {
        return $this->searchable;
    }

    /**
     * {@inheritDoc}
     */
    public function search(string|array|null $queries, array $options = []): static
    {
        if (is_string($queries)) {
            $queries = [
                'query' => $queries,
            ];
        }

        if (is_array($queries) && empty($queries) === false) {
            /** @var Scope|null $scope */
            if ($scope = $this->resolveScope('search')) {
                $this->addScopeQuery($scope::make($queries, $options), 'search');
            }
        }

        return $this;
    }

    /**
     * Add a search where clause to the query.
     *
     * @param Builder $query
     * @param string  $param
     * @param string  $column
     * @param mixed   $value
     * @param string  $boolean
     */
    public function createSearchClause(
        Builder $query,
        string  $param,
        string  $column,
        mixed   $value,
        string  $boolean = 'and'
    ): void {
        if ($param === 'query') {
            $query->where($this->appendTableName($column), self::$searchOperator, '%' . $value . '%', $boolean);
        } elseif (is_array($value)) {
            $query->whereIn($this->appendTableName($column), $value, $boolean);
        } else {
            $query->where($this->appendTableName($column), '=', $value, $boolean);
        }
    }

    /**
     * Add a search join to the query.
     *
     * @param Builder     $query
     * @param string      $joining_table
     * @param string      $foreign_key
     * @param string      $related_key
     * @param string|null $alias
     *
     * @return string
     */
    public function addSearchJoin(
        Builder     $query,
        string      $joining_table,
        string      $foreign_key,
        string      $related_key,
        string|null $alias = null
    ): string {
        // We need to join to the intermediate table
        $local_table = $this->getModel()->getTable();

        // Set the way the table will be joined, with an alias or without
        $table = $alias ? "{$joining_table} as {$alias}" : $joining_table;

        // Create an alias for the join
        $alias = $alias ?: $joining_table;

        // Create the join
        $query->join($table, "{$alias}.{$foreign_key}", "{$local_table}.{$related_key}");

        return $alias;
    }

    /**
     * Add a range clause to the query.
     *
     * @param Builder      $query
     * @param string|array $value
     * @param array        $columns
     *
     * @return bool
     */
    public function createSearchRangeClause(Builder $query, string|array $value, array $columns): bool
    {
        // Sanity check
        if (is_array($value) === true) {
            return false;
        }

        // Get the range type
        $range_type = strtolower(substr($value, 0, 2));

        // Perform a range based query if the range is valid
        // and the separator matches.
        if (substr($value, 2, 1) === ':' && in_array($range_type, $this->range_keys)) {
            // Get the true value
            $value = substr($value, 3);

            switch ($range_type) {
                case 'gt':
                    $query->where($this->appendTableName($columns[0]), '>', $value, 'and');
                    break;
                case 'lt':
                    $query->where($this->appendTableName($columns[0]), '<', $value, 'and');
                    break;
                case 'ne':
                    $query->where($this->appendTableName($columns[0]), '<>', $value, 'and');
                    break;
                case 'bt':
                    // Because this can only have two values
                    if (count($values = explode(',', $value)) === 2) {
                        $query->whereBetween($this->appendTableName($columns[0]), $values);
                    }
                    break;
            }

            return true;
        }

        return false;
    }
}
