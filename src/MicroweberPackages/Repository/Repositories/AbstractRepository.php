<?php


namespace MicroweberPackages\Repository\Repositories;

use Illuminate\Support\Facades\Event;
use MicroweberPackages\Content\Repositories\ContentRepository;
use MicroweberPackages\Repository\MicroweberQuery;
use MicroweberPackages\Repository\MicroweberQueryToModel;
use MicroweberPackages\Repository\Observers\RepositoryModelObserver;
use MicroweberPackages\Repository\Traits\CacheableRepository;

use Closure;
use BadMethodCallException;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\LaravelRepository\Traits\Cacheable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Torann\LaravelRepository\Contracts\RepositoryContract;
use Torann\LaravelRepository\Exceptions\RepositoryException;


abstract class AbstractRepository
{
    use CacheableRepository;


    /**
     * Cache expires constants
     */
    const EXPIRES_END_OF_DAY = 'eod';

    /**
     * Searching operator.
     *
     * This might be different when using a
     * different database driver.
     *
     * @var string
     */
    public static $searchOperator = 'LIKE';

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $modelInstance;

    /**
     * The errors message bag instance
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Global query scope.
     *
     * @var array
     */
    protected $scopeQuery = [];

    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [];

    /**
     * Default order by column and direction pairs.
     *
     * @var array
     */
    protected $orderBy = [];

    /**
     * One time skip of ordering. This is done when the
     * ordering is overwritten by the orderBy method.
     *
     * @var bool
     */
    protected $skipOrderingOnce = false;

    /**
     * A set of keys used to perform range queries.
     *
     * @var array
     */
    protected $range_keys = [
        'lt', 'gt',
        'bt', 'ne',
    ];

    public static $limit = 30;

    /**
     * Create a new Repository instance
     *
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
    public function boot()
    {


        Event::listen(['eloquent.saved: *', 'eloquent.created: *', 'eloquent.deleted: *'], function ($context) {
            $this->clearCache();
        });

        //    $this->getModel()::observe(RepositoryModelObserver::class);


    }

    /**
     * Return model instance.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->modelInstance;
    }

    /**
     * Reset internal Query
     *
     * @return $this
     */
    protected function scopeReset()
    {
        $this->scopeQuery = [];

        $this->query = $this->newQuery();

        return $this;
    }

    /**
     * Get a new entity instance
     *
     * @param array $attributes
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function getNew(array $attributes = [])
    {
        $this->errors = new MessageBag;

        return $this->modelInstance->newInstance($attributes);
    }

    /**
     * Get a new query builder instance with the applied
     * the order by and scopes.
     *
     * @param bool $skipOrdering
     *
     * @return self
     */
    public function newQuery($skipOrdering = false)
    {
        $this->query = $this->getNew()->newQuery();

        // Apply order by
        if ($skipOrdering === false && $this->skipOrderingOnce === false) {
            foreach ($this->getOrderBy() as $column => $dir) {
                $this->query->orderBy($column, $dir);
            }
        }

        // Reset the one time skip
        $this->skipOrderingOnce = false;

        $this->applyScope();

        return $this;
    }

    /**
     * Find data by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return Model|Collection
     */
    public function find($id, $columns = ['*'])
    {
        $this->newQuery();

        return $this->query->find($id, $columns);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param string $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $this->newQuery();

        if ($result = $this->query->find($id, $columns)) {
            return $result;
        }

        throw (new ModelNotFoundException)->setModel($this->model);
    }

    /**
     * Find data by field and value
     *
     * @param string $field
     * @param string $value
     * @param array $columns
     *
     * @return Model|Collection
     */
    public function findBy($field, $value, $columns = ['*'])
    {
        $this->newQuery();

        return $this->query->where($field, '=', $value)->first($columns);
    }

    /**
     * Find data by field
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findAllBy($attribute, $value, $columns = ['*'])
    {
        $this->newQuery();

        // Perform where in
        if (is_array($value)) {
            return $this->query->whereIn($attribute, $value)->get($columns);
        }

        return $this->query->where($attribute, '=', $value)->get($columns);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        $this->newQuery();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->query->where($field, $condition, $val);
            } else {
                $this->query->where($field, '=', $value);
            }
        }

        return $this->query->get($columns);
    }


    public static $_loaded_models_cache_get = [];


//
//    /**
//     * Find content by id.
//     *
//     * @param mixed $id
//     *
//     * @return Model|Collection
//     */


    public function findById($id)
    {


//        $cls = get_class($this->getModel());
//        if ($this->skippedCache() !== true) {
//            if (isset(self::$_loaded_models_cache_get[$cls][$id])) {
//                return self::$_loaded_models_cache_get[$cls][$id];
//            }
//        }
        $this->newQuery();
        return $this->query
            ->where('id', $id)
            ->limit(1)
            ->first();

        //  return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
//        return self::$_loaded_models_cache_get[$cls][$id] = $this->query
//            ->where('id', $id)
//            ->limit(1)
//            ->first();


        //  });
    }

    /**
     * Order results by.
     *
     * @param string $column
     * @param string $direction
     *
     * @return self
     */
    public function orderBy($column, $direction)
    {
        // Ensure the sort is valid
        if (in_array($column, $this->getOrderable()) === false
            && array_key_exists($column, $this->getOrderable()) === false
        ) {
            return $this;
        }

        // One time skip
        $this->skipOrderingOnce = true;

        return $this->addScopeQuery(function ($query) use ($column, $direction) {

            // Get valid sort order
            $direction = in_array(strtolower($direction), ['desc', 'asc']) ? $direction : 'asc';

            // Check for table column mask
            $column = Arr::get($this->getOrderable(), $column, $column);

            return $query->orderBy($this->appendTableName($column), $direction);
        });
    }

    /**
     * Return the order by array.
     *
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Return orderable array.
     *
     * @return array
     */
    public function getOrderable()
    {
        return $this->orderable;
    }

    /**
     * Filter results by given query params.
     *
     * @param string|array $queries
     *
     * @return self
     */
    public function search($queries)
    {
        // Adjust for simple search queries
        if (is_string($queries)) {
            $queries = [
                'query' => $queries,
            ];
        }

        return $this->addScopeQuery(function ($query) use ($queries) {
            // Keep track of what tables have been joined and their aliases
            $joined = [];

            foreach ($this->getModel()->getSearchable() as $param => $columns) {
                // It doesn't always have to map to something
                $param = is_numeric($param) ? $columns : $param;

                // Get param value
                $value = Arr::get($queries, $param, '');

                // Validate value
                if ($value === '' || $value === null) {
                    continue;
                }

                // Columns should be an array
                $columns = (array)$columns;

                // Loop though the columns and look for relationships
                foreach ($columns as $key => $column) {
                    @list($joining_table, $options) = explode(':', $column);

                    if ($options !== null) {
                        @list($column, $foreign_key, $related_key, $alias) = explode(',', $options);

                        // Join the table if it hasn't already been joined
                        if (isset($joined[$joining_table]) == false) {
                            $joined[$joining_table] = $this->addSearchJoin(
                                $query,
                                $joining_table,
                                $foreign_key,
                                $related_key ?: $param, // Allow for related key overriding
                                $alias
                            );
                        }

                        // Set a new column search
                        $columns[$key] = "{$joined[$joining_table]}.{$column}";
                    }
                }

                // Perform a range based query if the range is valid
                // and the separator matches.
                if ($this->createSearchRangeClause($query, $value, $columns)) {
                    continue;
                }

                // Create standard query
                if (count($columns) > 1) {
                    $query->where(function ($q) use ($columns, $param, $value) {
                        foreach ($columns as $column) {
                            $this->createSearchClause($q, $param, $column, $value, 'or');
                        }
                    });
                } else {
                    $this->createSearchClause($query, $param, $columns[0], $value);
                }
            }

            // Ensure only the current model's table attributes are return
            if (!$this->_selectIsAdded) {
                $query->addSelect([
                    $this->getModel()->getTable() . '.*',
                ]);
            }
            return $query;
        });
    }


    private $_selectIsAdded = false;

    public function select($columns)
    {
        $this->_selectIsAdded = true;
        return $this->addScopeQuery(function ($query) use ($columns) {
            foreach ($columns as $key => $column) {
                $query->addSelect([
                    $this->getModel()->getTable() . '.' . $column,
                ]);
            }

            return $query;

        });


    }

    /**
     * Set the "limit" value of the query.
     *
     * @param int $limit
     *
     * @return self
     */
    public function limit($limit)
    {
        return $this->addScopeQuery(function ($query) use ($limit) {
            return $query->limit($limit);
        });
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = ['*'])
    {
        $this->newQuery();

        return $this->query->get($columns);
    }

    /**
     * Retrieve the "count" result of the query.
     *
     * @param array $columns
     *
     * @return int
     */
    public function count($columns = ['*'])
    {
        $this->newQuery();

        return $this->query->count($columns);
    }

    /**
     * Get an array with the values of a given column.
     *
     * @param string $value
     * @param string $key
     *
     * @return array
     */
    public function pluck($value, $key = null)
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
     * @param int $per_page
     * @param array $columns
     * @param string $page_name
     * @param int|null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($per_page = null, $columns = ['*'], $page_name = 'page', $page = null)
    {
        // Get the default per page when not set
        $per_page = $per_page ?: config('repositories.per_page', 15);

        // Get the per page max
        $per_page_max = config('repositories.max_per_page', 100);

        // Ensure the user can never make the per
        // page limit higher than the defined max.
        if ($per_page > $per_page_max) {
            $per_page = $per_page_max;
        }

        $this->newQuery();

        return $this->query->paginate($per_page, $columns, $page_name, $page);
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param int $per_page
     * @param array $columns
     * @param string $page_name
     * @param int|null $page
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($per_page = null, $columns = ['*'], $page_name = 'page', $page = null)
    {
        $this->newQuery();

        // Get the default per page when not set
        $per_page = $per_page ?: config('repositories.per_page', 15);

        return $this->query->simplePaginate($per_page, $columns, $page_name, $page);
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return Model|bool
     */
    public function create(array $attributes)
    {
        $entity = $this->getNew($attributes);

        if ($entity->save()) {
            $this->clearCache();

            return $entity;
        }

        return false;
    }

    /**
     * Update an entity with the given attributes and persist it
     *
     * @param Model $entity
     * @param array $attributes
     *
     * @return bool
     */
    public function update(Model $entity, array $attributes)
    {
        if ($entity->update($attributes)) {
            $this->clearCache();

            return true;
        }

        return false;
    }

    /**
     * Delete a entity in repository
     *
     * @param mixed $entity
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete($entity)
    {
        if (($entity instanceof Model) === false) {
            $entity = $this->find($entity);
        }

        if ($entity->delete()) {
            $this->clearCache();

            return true;
        }

        return false;
    }

    /**
     * Create model instance.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        if (empty($this->model)) {
            throw new RepositoryException('The model class must be set on the repository.');
        }

        return $this->modelInstance = new $this->model;
    }

    /**
     * Get a new query builder instance with the applied
     * the order by and scopes.
     *
     * @param bool $skipOrdering
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilder(bool $skipOrdering = false)
    {
        $this->newQuery($skipOrdering);

        return $this->query;
    }

    /**
     * Get the raw SQL statements for the request
     *
     * @return string
     */
    public function toSql()
    {
        $this->newQuery();

        return $this->query->toSql();
    }

    /**
     * Return query scope.
     *
     * @return array
     */
    public function getScopeQuery()
    {
        return $this->scopeQuery;
    }

    /**
     * Add query scope.
     *
     * @param Closure $scope
     *
     * @return $this
     */
    public function addScopeQuery(Closure $scope)
    {
        $this->scopeQuery[] = $scope;

        return $this;
    }

    /**
     * Apply scope in current Query
     *
     * @return $this
     */
    protected function applyScope()
    {
        foreach ($this->scopeQuery as $callback) {
            if (is_callable($callback)) {
                $this->query = $callback($this->query);
            }
        }

        // Clear scopes
        $this->scopeQuery = [];

        return $this;
    }

    /**
     * Add a message to the repository's error messages.
     *
     * @param string $message
     * @param string $key
     *
     * @return self
     */
    public function addError($message, string $key = 'message')
    {
        $this->getErrors()->add($key, $message);

        return $this;
    }

    /**
     * Get the repository's error messages.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        if ($this->errors === null) {
            $this->errors = new MessageBag;
        }

        return $this->errors;
    }

    /**
     * Get the repository's first error message.
     *
     * @param string $default
     *
     * @return string
     */
    public function getErrorMessage($default = '')
    {
        return $this->getErrors()->first('message') ?: $default;
    }

    /**
     * Append table name to column.
     *
     * @param string $column
     *
     * @return string
     */
    protected function appendTableName($column)
    {
        // If missing prepend the table name
        if (strpos($column, '.') === false) {
            return $this->modelInstance->getTable() . '.' . $column;
        }

        // Remove alias prefix indicator
        if (preg_match('/^_\./', $column) != false) {
            return preg_replace('/^_\./', '', $column);
        }

        return $column;
    }

    /**
     * Add a search where clause to the query.
     *
     * @param Builder $query
     * @param string $param
     * @param string $column
     * @param string $value
     * @param string $boolean
     */
    protected function createSearchClause(Builder $query, $param, $column, $value, $boolean = 'and')
    {
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
     * @param Builder $query
     * @param string $joining_table
     * @param string $foreign_key
     * @param string $related_key
     * @param string $alias
     *
     * @return string
     */
    protected function addSearchJoin(Builder $query, $joining_table, $foreign_key, $related_key, $alias)
    {
        // We need to join to the intermediate table
        $local_table = $this->getModel()->getTable();

        // Set the way the table will be join, with an alias or without
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
     * @param Builder $query
     * @param string $value
     * @param array $columns
     *
     * @return bool
     */
    protected function createSearchRangeClause(Builder $query, $value, array $columns)
    {
        // Skip arrays
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

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // Check for scope method and call
        if (method_exists($this, $scope = 'scope' . ucfirst($method))) {
            return call_user_func_array([$this, $scope], $parameters);
        }

        $className = get_class($this);

        throw new BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }


    /**
     * Filter results by given query params.
     *
     * @param string|array $queries
     *
     * @return self
     */
    public function getByParams($params = [])
    {
        $result = $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {

            $searchable = [];
            $model = $this->getModel();
            $table = $model->getTable();

            $params = self::unifyParams($params);

            $columns = $model->getFillable();
            if (method_exists($model, 'getSearchable')) {
                $searchable = $model->getSearchable();
            }
            if (is_string($params)) {
                $params = parse_params($params);
            }

            $this->newQuery();
            $this->query = self::querySelectLogic($this->query, $table, $columns, $params);

            if ($params) {
                foreach ($params as $paramKey => $paramValue) {
                    if (isset($this->filterMethods[$paramKey])) {
                        $whereMethodName = $this->filterMethods[$paramKey];
                        $this->query->$whereMethodName($paramValue);
                        unset($params[$paramKey]);
                    } else {

                        if (in_array($paramKey, $searchable)) {
                            $parseCompareSign = db_query_parse_compare_sign_value($paramValue);
                            $this->query->where($table . '.' . $paramKey, $parseCompareSign['compare_sign'], $parseCompareSign['value']);
                        }
                    }
                }
            }

            $this->query = self::queryKeywordLogic($this->query, $table, $columns, $params);
            $this->query = self::queryTagsLogic($this->query, $table, $columns, $params);
            $this->query = self::queryClosureLogic($this->query, $table, $columns, $params);
            $this->query = self::queryExcludeIdsLogic($this->query, $table, $columns, $params);
            $this->query = self::queryIncludeIdsLogic($this->query, $table, $columns, $params);

            $this->query = self::queryLimitLogic($this->query, $table, $columns, $params);
            $this->query = self::queryGroupByLogic($this->query, $table, $columns, $params);
            $this->query = self::queryOrderByLogic($this->query, $table, $columns, $params);


            $single = false;
            $multiple = false;
            $count = false;
            if (isset($params['count']) and $params['count']) {
                $exec = $this->query->count();
                $count = true;
            } else if (isset($params['page_count'])) {


                $limit = self::$limit;
                if (isset($params['limit']) and ($params['limit'] != 'no_limit')) {
                    $limit = intval($params['limit']);
                }
                $page_count = $this->query->count();
                if($page_count > 0){
                    $exec =intval(ceil($page_count / $limit));
                } else {
                    $exec =0;
                }


                $count = true;
            } else if (isset($params['single']) || isset($params['one'])) {
                $exec = $this->query->first();
                $single = true;
            } else {
                $exec = $this->query->get();
                $multiple = true;
            }

            $result = [];
            if ($exec != null) {
                if (is_numeric($exec)) {
                    $result = $exec;
                } else {
                    $result = $exec->toArray();
                }
            }

            if ($single || $multiple) {
                $hookParams = [];
                $hookParams['data'] = $result;
                if ($single) {
                    $hookParams['hook_overwrite_type'] = 'single';
                } else {
                    $hookParams['hook_overwrite_type'] = 'multiple';
                }
                if (is_array($result)) {
                    $overwrite = app()->event_manager->response(get_class($this) . '\\getByParams', $hookParams);
                    if (isset($overwrite['data'])) {
                        //     $result = $overwrite['data'];
                    }
                }
            }

            if ($count) {
                if (!is_numeric($result)) {
                    return 0;
                }
                return $result;
            }

            if (!empty($result)) {
                return $result;
            }

            return [];
        });

        if (!empty($result)) {
            return $result;
        }

        return null;

    }

    public static function unifyParams($params)
    {
        if (is_array($params)) {
            if (isset($params['count_paging'])) {
                $params['page_count'] = $params['count_paging'];
                unset($params['count_paging']);
            }

            if (isset($params['orderby'])) {
                $params['order_by'] = $params['orderby'];
                unset($params['orderby']);
            }

            if (!isset($params['current_page']) and isset($params['page'])) {
                // old parameter 'page', must be removed and 'current_page' must be used
                $params['current_page'] = $params['page'];
                unset($params['page']);
            }

            if (!isset($params['no_limit']) and isset($params['nolimit'])) {
                $params['no_limit'] = $params['nolimit'];
                unset($params['nolimit']);
            }
        }
        return $params;
    }

    public static function queryLimitLogic($model, $table, $columns, $params)
    {

        $limit = self::$limit;
        $no_limit = false;

        if (isset($params['no_limit'])) {
            $no_limit = true;
        }
        if (!isset($params['page_count'])) {
            if (!$no_limit) {
                if (isset($params['limit']) and ($params['limit'] == 'nolimit' or $params['limit'] == 'no_limit')) {
                    $no_limit = true;
                    unset($params['limit']);
                }

                if (isset($params['limit']) and $params['limit']) {
                    $limit = intval($params['limit']);
                }
            }

            if (!$no_limit) {
                $model->limit($limit);

                if (isset($params['paging_param']) and $params['paging_param']) {
                    if (isset($params[$params['paging_param']]) and $params[$params['paging_param']]) {
                        $params['current_page'] = $params[$params['paging_param']];
                    }
                }


                if (isset($params['current_page']) and $params['current_page']) {

                    $currentPageValue = intval($params['current_page']);
                    if ($currentPageValue > 1) {
                        $criteria = intval($currentPageValue - 1) * intval($limit);

                        $model->skip($criteria);
                    }
                }

            }
        }

        return $model;
    }

    public static function queryOrderByLogic($model, $table, $columns, $params)
    {

        if (isset($params['order_by']) and is_string($params['order_by'])) {
            $orderBy = trim($params['order_by']);
            $orderByCriteria = explode(',', $orderBy);
            foreach ($orderByCriteria as $c) {
                $c = urldecode($c);
                $c = explode(' ', $c);
                if (isset($c[0]) and trim($c[0]) != '') {
                    $c[0] = trim($c[0]);
                    if (isset($c[1])) {
                        $c[1] = trim($c[1]);
                    }
                    if (isset($c[1]) and ($c[1]) != '') {
                        $model->orderBy($c[0], $c[1]);
                    } elseif (isset($c[0])) {
                        $model->orderBy($c[0]);
                    }
                }
            }
        }

        return $model;
    }

    public static function queryGroupByLogic($model, $table, $columns, $params)
    {

        if (isset($params['group_by']) and is_string($params['group_by'])) {
            $groupByCriteria = explode(',', $params['group_by']);
            if (!empty($groupByCriteria)) {
                $groupByCriteria = array_map('trim', $groupByCriteria);
            }
            if (!empty($groupByCriteria)) {
                $model->groupBy($groupByCriteria);
            }
        }

        return $model;
    }

    public static function queryExcludeIdsLogic($model, $table, $columns, $params)
    {

        $excludeIds = [];
        if (isset($params['exclude_ids']) and is_string($params['exclude_ids'])) {
            $excludeIdsMerge = explode(',', $params['exclude_ids']);
            if ($excludeIdsMerge) {
                $excludeIds = array_merge($excludeIds, $excludeIdsMerge);
            }
        } else if (isset($params['exclude_ids']) and is_array($params['exclude_ids'])) {
            $excludeIds = array_merge($excludeIds, $params['exclude_ids']);
        }
        if (!empty($excludeIds)) {
            $model->whereNotIn($table . '.id', $excludeIds);
        }

        return $model;
    }

    public static function queryIncludeIdsLogic($model, $table, $columns, $params)
    {

        $includeIds = [];
        if (isset($params['ids']) and is_string($params['ids'])) {
            $excludeIdsMerge = explode(',', $params['ids']);
            if ($excludeIdsMerge) {
                $includeIds = array_merge($includeIds, $excludeIdsMerge);
            }
        } else if (isset($params['ids']) and is_array($params['ids'])) {
            $includeIds = array_merge($includeIds, $params['ids']);
        }
        if (!empty($includeIds)) {
            $model->whereIn($table . '.id', $includeIds);
        }

        return $model;
    }

    public static function queryKeywordLogic($model, $table, $columns, $params)
    {

        if (isset($params['keyword'])) {
            // FilterByKeywordTrait
            $model->filter(['keyword' => $params['keyword']]);
        }

        return $model;
    }

    public static function queryTagsLogic($model, $table, $columns, $params)
    {

        if (isset($params['tags'])) {
            $model->filter(['tags' => $params['tags']]);
        }

        if (isset($params['tag_names'])) {
            $model->filter(['tags' => $params['tag_names']]);
        }

        if (isset($params['all_tags'])) {
            $model->filter(['allTags' => $params['all_tags']]);
        }


        return $model;
    }

    public static function queryClosureLogic($model, $table, $columns, $params)
    {

        foreach ($params as $paramKey => $paramValue) {
            if (is_object($params[$paramKey]) && ($params[$paramKey] instanceof \Closure)) {
                $model = call_user_func($params[$paramKey], $model, $params);
            }
        }

        return $model;
    }

    public static function querySelectLogic($model, $table, $columns, $params)
    {
        if (isset($params['fields']) and $params['fields'] != false) {
            if (is_string($params['fields'])) {
                $isFields = explode(',', $params['fields']);
            } else {
                $isFields = $params['fields'];
            }
            $isFieldsQ = [];
            if ($isFields) {
                foreach ($isFields as $isField) {
                    if (is_string($isField)) {
                        $isField = trim($isField);
                        if ($isField != '') {
                            $isFieldsQ[] = $table . '.' . $isField;
                        }
                    }
                }
            }
            if ($isFieldsQ) {
                $model->select($isFieldsQ);
            }
        } else {
            $model->select($table . '.*');
        }

        return $model;
    }

    public function getIdsThatHaveRelation($table, $rel_type)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($table, $rel_type) {

            $result = [];

            $translation_namespaces = \DB::table($table)
                ->select('rel_id')
                ->where('rel_type', $rel_type)
                ->groupBy('rel_id')
                ->get();

            if ($translation_namespaces) {
                $result = $translation_namespaces->toArray();
                if ($result and is_array($result)) {
                    $result = array_map(function ($value) {
                        return (array)$value;
                    }, $result);
                    $result = array_values($result);
                    $result = array_flatten($result);
                    $result = array_filter($result);
                    if (!empty($result)) {
                        $result = array_flip($result);
                        $result = array_keys($result);

                    }
                }

            }

            return $result;
        });

    }

    public function getById($id)
    {

        if (is_array($id)) {
            $ready = [];
            foreach ($id as $k => $v) {
                $get =  $this->getById($v);
                if($get){
                    $ready[$k] = $get;
                }
            }
            return $ready;
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            if (!$id) {
                return false;
            }

            if (intval($id) == 0) {
                return false;
            }

            if (is_numeric($id)) {
                $id = intval($id);
            } else {
                $id = mb_trim($id);
            }

            $table = $this->getModel()->getTable();
            $getItemById = \DB::table($table)->where('id', $id)->first();

            if ($getItemById) {

                $getItemById = (array)$getItemById;

                // hook
                $hookParams = [];
                $hookParams['data'] = $getItemById;
                $hookParams['hook_overwrite_type'] = 'single';

                if (is_array($getItemById)) {
                    $overwrite = app()->event_manager->response(get_class($this) . '\\getById', $hookParams);
                    if (isset($overwrite['data'])) {
                        $getItemById = $overwrite['data'];
                    }
                }

                return $getItemById;
            }

            return false;
        });
    }

    /*  public function getByParams($params = [])
      {
         return $this->cacheCallback(get_class($this).__FUNCTION__, func_get_args(), function () use ($params) {
             $this->newQuery();
             return MicroweberQuery::execute($this->query, $params);
          });
      }*/

}
