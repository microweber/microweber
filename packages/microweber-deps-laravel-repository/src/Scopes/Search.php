<?php

namespace Torann\LaravelRepository\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Torann\LaravelRepository\Contracts\Repository;

class Search extends Scope
{
    private array $queries;

    /**
     * @param array $queries
     * @param array $options
     */
    public function __construct(array $queries, array $options = [])
    {
        $this->queries = array_filter($queries, function ($value) {
            return blank($value) === false;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkip(): bool
    {
        return empty($this->queries);
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder, Repository $repository): Builder
    {
        $searchable = $this->getSearchable($repository);

        foreach ($this->queries as $param => $value) {
            // Validate and get the columns used for the search
            if (blank($columns = $searchable[$param] ?? null)) {
                continue;
            }

            $columns = $this->processColumns(
                (array) $columns, $param, $builder, $repository
            );

            // Perform a range based query if the range is valid
            // and the separator matches.
            if ($repository->createSearchRangeClause($builder, $value, $columns)) {
                continue;
            }

            if (count($columns) > 1) {
                $builder->where(function ($q) use ($columns, $param, $value, $repository) {
                    foreach ($columns as $column) {
                        $repository->createSearchClause($q, $param, $column, $value, 'or');
                    }
                });
            } elseif (empty($columns) === false) {
                $repository->createSearchClause($builder, $param, $columns[0], $value);
            }
        }

        // Ensure only the current model's table attributes are return
        $builder->addSelect([
            $repository->getModel()->getTable() . '.*',
        ]);

        return $builder;
    }

    /**
     * Loop though the columns and look for relationships
     *
     * @param Repository $repository
     *
     * @return array
     */
    private function processColumns(array $columns, string $param, Builder $builder, Repository $repository): array
    {
        foreach ($columns as $key => $column) {
            @list($joining_table, $options) = explode(':', $column);

            if ($options !== null) {
                // Parse the column and options
                @[$column, $options] = explode(',', $options, 2);

                $alias = $repository->addJoin($joining_table, function ($joining_table) use (
                    $builder,
                    $options,
                    $param,
                    $repository
                ) {
                    // Parse the joining values
                    @[$foreign_key, $related_key, $alias] = explode(',', $options);

                    return $repository->addSearchJoin(
                        $builder,
                        $joining_table,
                        $foreign_key,
                        $related_key ?: $param, // Allow for related key overriding
                        $alias
                    );
                });

                // Set a new column search
                $columns[$key] = "{$alias}.{$column}";
            }
        }

        return $columns;
    }

    /**
     * Flatten and standardize the searchable array
     *
     * @param Repository $repository
     *
     * @return array
     */
    private function getSearchable(Repository $repository): array
    {
        $searchable = [];

        foreach ($repository->getSearchable() as $param => $columns) {
            $searchable[is_numeric($param) ? $columns : $param] = (array) $columns;
        }

        return $searchable;
    }
}
