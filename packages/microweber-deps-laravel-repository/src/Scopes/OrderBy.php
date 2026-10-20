<?php

namespace Torann\LaravelRepository\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Torann\LaravelRepository\Contracts\Repository;

class OrderBy extends Scope
{
    private string|array $column;
    private string $direction = 'asc';

    /**
     * @param mixed       $column
     * @param string|null $direction
     */
    public function __construct(mixed $column, string|null $direction)
    {
        $this->column = $column;

        $this->setDirection($direction);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkip(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder, Repository $repository): Builder
    {
        $column = $this->column;

        if (is_scalar($column) && str_contains($column, ':')) {
            $column = $this->getColumn($builder, $repository);
        }

        return $builder->orderBy(
            $repository->appendTableName($column), $this->direction
        );
    }

    /**
     * @param string|null $direction
     *
     * @return $this
     */
    private function setDirection(string|null $direction): static
    {
        $this->direction = in_array(strtolower($direction), ['desc', 'asc'])
            ? $direction
            : 'asc';

        return $this;
    }

    /**
     * @param Builder    $builder
     * @param Repository $repository
     *
     * @return string
     */
    private function getColumn(Builder $builder, Repository $repository): string
    {
        @[$joining_table, $options] = explode(':', $this->column);

        if ($options !== null) {
            // Parse the column and options
            @[$column, $options] = explode(',', $options, 2);

            $alias = $repository->addJoin($joining_table, function ($joining_table) use (
                $builder,
                $options,
                $repository
            ) {
                // Parse the joining values
                @[$foreign_key, $related_key, $alias] = explode(',', $options);

                return $repository->addOrderByJoin(
                    $builder,
                    $joining_table,
                    $foreign_key,
                    $related_key,
                    $alias
                );
            });

            // Set the new column
            return "{$alias}.{$column}";
        }

        return $this->column;
    }
}
