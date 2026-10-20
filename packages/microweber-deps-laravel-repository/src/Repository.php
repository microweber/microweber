<?php

namespace Torann\LaravelRepository;

use Closure;
use BadMethodCallException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Torann\LaravelRepository\Exceptions\RepositoryException;
use Torann\LaravelRepository\Contracts\Repository as RepositoryContract;

abstract class Repository implements RepositoryContract
{
    use Concerns\Cacheable;
    use Concerns\Searching;
    use Concerns\Messages;
    use Concerns\Ordering;
    use Concerns\Scopes;

    protected Model|null $modelInstance = null;
    protected Builder|null $query = null;

    /**
     * Cache expires constants
     */
    const EXPIRES_END_OF_DAY = 'eod';

    /**
     * Keep track of what tables have been joined and their aliases.
     */
    protected array $join_aliases = [];

    /**
     * @throws RepositoryException
     */
    public function __construct()
    {
        $this->makeModel();
        $this->boot();
    }

    /**
     * The "booting" method of the repository.
     */
    public function boot(): void
    {
        $loaded = [];

        foreach (class_uses_recursive($this) as $trait) {
            $method = 'boot' . class_basename($trait);

            if (method_exists($this, $method) && in_array($method, $loaded) === false) {
                $this->{$method}();

                $loaded[] = $method;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getModel(): Model
    {
        return $this->modelInstance;
    }

    /**
     * Get a new entity instance
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function getNew(array $attributes = []): Model
    {
        $this->message_bag = null;

        return $this->modelInstance->newInstance($attributes);
    }

    /**
     * Get a new query builder instance with the applied
     * the order by and scopes.
     *
     * @param bool $skipOrdering
     *
     * @return static
     */
    public function newQuery(bool $skipOrdering = false): static
    {
        $this->join_aliases = [];

        $this->query = $this->getNew()->newQuery();

        // Apply order by scope. Only one order by is allowed, for more create a
        // new scope or override the method.
        if ($skipOrdering === false) {
            if (empty($order_by = $this->getOrderBy()) === false) {
                $this->applyOrderableScope(
                    key($order_by), reset($order_by)
                );
            }
        }

        $this->applyScope();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function find(mixed $id, array $columns = ['*'])
    {
        $this->newQuery();

        return $this->query->find($id, $columns);
    }

    /**
     * {@inheritDoc}
     */
    public function findOrFail(mixed $id, array $columns = ['*']): Model|null
    {
        $this->newQuery();

        if ($result = $this->query->find($id, $columns)) {
            return $result;
        }

        // @phpstan-ignore-next-line
        throw (new ModelNotFoundException)->setModel($this->model);
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'])
    {
        $this->newQuery();

        return $this->query->where($field, '=', $value)->first($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function findAllBy(string $attribute, mixed $value, array $columns = ['*'])
    {
        $this->newQuery();

        if (is_array($value)) {
            return $this->query->whereIn($attribute, $value)->get($columns);
        }

        return $this->query->where($attribute, '=', $value)->get($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function findWhere(array $where, array $columns = ['*'])
    {
        $this->newQuery();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                [$field, $condition, $val] = $value;
                $this->query->where($field, $condition, $val);
            } else {
                $this->query->where($field, '=', $value);
            }
        }

        return $this->query->get($columns);
    }

    /**
     * Set the "limit" value of the query.
     *
     * @param mixed $limit
     *
     * @return static
     */
    public function limit(mixed $limit): static
    {
        if ($limit = ((int) $limit)) {
            return $this->addScopeQuery(function ($query) use ($limit) {
                return $query->limit($limit);
            });
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function all(array $columns = ['*']): Collection
    {
        $this->newQuery();

        return $this->query->get($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function count(array $columns = ['*']): int
    {
        $this->newQuery();

        return $this->query->count($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function pluck(string $value, string|null $key = null): array
    {
        $this->newQuery();

        $lists = $this->query->pluck($value, $key);

        if (is_array($lists)) {
            return $lists;
        }

        return $lists->all();
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param mixed        $per_page
     * @param array|string $columns
     * @param string       $page_name
     * @param mixed        $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(
        mixed        $per_page = null,
        string|array $columns = ['*'],
        string       $page_name = 'page',
        mixed        $page = null
    ) {
        // Get the default per page when not set
        $per_page = ((int) $per_page) ?: config('repositories.per_page', 15);

        // Get the per page max
        $per_page_max = ((int) config('repositories.max_per_page', 100)) ?: 100;

        // Ensure the user can never make the per
        // page limit higher than the defined max.
        if ($per_page > $per_page_max) {
            $per_page = $per_page_max;
        }

        $this->newQuery();

        return $this->query->paginate($per_page, $columns, $page_name, ((int) $page));
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param mixed        $per_page
     * @param string|array $columns
     * @param string       $page_name
     * @param mixed        $page
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate(
        mixed        $per_page = null,
        string|array $columns = ['*'],
        string       $page_name = 'page',
        mixed        $page = null
    ) {
        $this->newQuery();

        // Get the default per page when not set
        $per_page = ((int) $per_page) ?: config('repositories.per_page', 15);

        return $this->query->simplePaginate($per_page, $columns, $page_name, ((int) $page));
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes): Model|bool
    {
        $entity = $this->getNew($attributes);

        if ($entity->save()) {
            $this->flushCache();

            return $entity;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function update(Model $entity, array $attributes): Model|bool
    {
        if ($entity->update($attributes)) {
            $this->flushCache();

            return $entity;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(mixed $entity): bool
    {
        if (($entity instanceof Model) === false) {
            $entity = $this->find($entity);
        }

        if ($entity->delete()) {
            $this->flushCache();

            return true;
        }

        return false;
    }

    /**
     * Create model instance.
     *
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel(): Model
    {
        if (empty($this->model)) {
            throw new RepositoryException('The model class must be set on the repository.');
        }

        return $this->modelInstance = new $this->model;
    }

    /**
     * Get a new query builder instance with the applied scopes order by and scopes.
     *
     * @param bool $skipOrdering
     *
     * @return Builder
     */
    public function getBuilder(bool $skipOrdering = false): Builder
    {
        $this->newQuery($skipOrdering);

        return $this->query;
    }

    /**
     * Get the raw SQL statements for the request
     *
     * @return string
     */
    public function toSql(): string
    {
        $this->newQuery();

        return $this->query->toSql();
    }

    /**
     * @param string  $alias
     * @param Closure $callback
     *
     * @return string
     */
    public function addJoin(string $alias, Closure $callback): string
    {
        if (isset($this->join_aliases[$alias]) == false) {
            $this->join_aliases[$alias] = $callback($alias);
        }

        return $this->join_aliases[$alias];
    }

    /**
     * Append table name to column.
     *
     * @param string $column
     *
     * @return string
     */
    public function appendTableName(string $column): string
    {
        // If missing prepend the table name
        if (str_contains($column, '.') === false) {
            return $this->modelInstance->getTable() . '.' . $column;
        }

        // Remove alias prefix indicator
        if (preg_match('/^_\./', $column) != false) {
            return preg_replace('/^_\./', '', $column);
        }

        return $column;
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // Check for scope method and call
        if (method_exists($this, $scope = 'scope' . ucfirst($method))) {
            return call_user_func_array([$this, $scope], $parameters);
        }

        $class_name = get_class($this);

        throw new BadMethodCallException("Call to undefined method {$class_name}::{$method}()");
    }
}
