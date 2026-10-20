<?php

namespace Torann\LaravelRepository\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 */
interface Repository
{
    /**
     * Return model instance.
     *
     * @return Model
     */
    public function getModel(): Model;

    /**
     * Find data by id
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return Model|Collection
     */
    public function find(mixed $id, array $columns = ['*']);

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return Model|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(mixed $id, array $columns = ['*']): Model|null;

    /**
     * Find data by field and value
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Model|object|static|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']);

    /**
     * Find data by field
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllBy(string $attribute, mixed $value, array $columns = ['*']);

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findWhere(array $where, array $columns = ['*']);

    /**
     * Order results by.
     *
     * @param mixed       $column
     * @param string|null $direction
     *
     * @return static
     */
    public function orderBy(mixed $column, string|null $direction): static;

    /**
     * Filter results by given query params.
     *
     * @param string|array|null $queries
     * @param array             $options
     *
     * @return static
     */
    public function search(string|array|null $queries, array $options = []): static;

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Retrieve the "count" result of the query.
     *
     * @param array $columns
     *
     * @return int
     */
    public function count(array $columns = ['*']): int;

    /**
     * Get an array with the values of a given column.
     *
     * @param string      $value
     * @param string|null $key
     *
     * @return array<TKey, TValue>
     */
    public function pluck(string $value, string|null $key = null): array;

    /**
     * Retrieve all data of repository, paginated
     *
     * @param mixed        $per_page
     * @param string|array $columns
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function paginate(mixed $per_page = null, string|array $columns = ['*']);

    /**
     * Retrieve all data of repository, paginated
     *
     * @param mixed        $per_page
     * @param string|array $columns
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate(mixed $per_page = null, string|array $columns = ['*']);

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return Model|bool
     */
    public function create(array $attributes): Model|bool;

    /**
     * Update an entity with the given attributes and persist it
     *
     * @param Model $entity
     * @param array $attributes
     *
     * @return Model|bool
     */
    public function update(Model $entity, array $attributes): Model|bool;

    /**
     * Delete a entity in repository
     *
     * @param mixed $entity
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(mixed $entity): bool;

    /**
     * Get the raw SQL statements for the request
     *
     * @return string
     */
    public function toSql(): string;

    /**
     * @return MessageBag
     */
    public function getMessageBag(): MessageBag;

    /**
     * @param string $message
     * @param string $key
     *
     * @return static
     */
    public function addMessage(string $message, string $key = 'message'): static;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasMessage(string $key = 'message'): bool;

    /**
     * @param string|null $key
     * @param string|null $format
     * @param string      $default
     *
     * @return string
     */
    public function getMessage(string|null $key = null, string|null $format = null, string $default = ''): string;

    /**
     * Add an error to the message bag
     *
     * @return static
     */
    public function addError(string $message): static;

    /**
     * Determine if any errors were reported
     *
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * Get all error messages.
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     * Get a single error message.
     *
     * @param string $default
     *
     * @return string
     */
    public function getErrorMessage(string $default = ''): string;
}
