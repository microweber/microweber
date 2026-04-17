<?php

namespace MicroweberPackages\Repository\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * FilterableByParams — Eloquent model trait providing param-based query filtering.
 *
 * Extracted from AbstractRepository's static query methods so that
 * filtering logic lives on the model (as scopes) rather than in the repository layer.
 *
 * Usage on a model:
 *   use FilterableByParams;
 *
 *   $results = MyModel::filterByParams(['limit' => 10, 'order_by' => 'id desc'])->get();
 */
trait FilterableByParams
{
    public static int $defaultLimit = 30;

    /**
     * Apply a full set of param-based filters to the query.
     *
     * Supports: fields, keyword, tags, closure, exclude_ids, ids,
     * limit, current_page, no_limit, group_by, order_by, count, single/one.
     */
    public function scopeFilterByParams(Builder $query, array $params = []): Builder
    {
        $table = $this->getTable();
        $columns = $this->getFillable();
        $searchable = method_exists($this, 'getSearchable') ? $this->getSearchable() : [];

        $params = static::unifyFilterParams($params);

        $query = static::applySelectLogic($query, $table, $params);

        if ($params) {
            $filterMethods = property_exists($this, 'filterMethods') ? $this->filterMethods : [];
            foreach ($params as $paramKey => $paramValue) {
                if (isset($filterMethods[$paramKey])) {
                    $whereMethodName = $filterMethods[$paramKey];
                    $query->$whereMethodName($paramValue);
                    unset($params[$paramKey]);
                } elseif (in_array($paramKey, $searchable)) {
                    $parseCompareSign = db_query_parse_compare_sign_value($paramValue);
                    $query->where($table . '.' . $paramKey, $parseCompareSign['compare_sign'], $parseCompareSign['value']);
                }
            }
        }

        $query = static::applyKeywordLogic($query, $params);
        $query = static::applyTagsLogic($query, $params);
        $query = static::applyClosureLogic($query, $params);
        $query = static::applyExcludeIdsLogic($query, $table, $params);
        $query = static::applyIncludeIdsLogic($query, $table, $params);
        $query = static::applyLimitLogic($query, $params);
        $query = static::applyGroupByLogic($query, $params);
        $query = static::applyOrderByLogic($query, $params);

        return $query;
    }

    /**
     * Normalize legacy param keys to canonical form.
     */
    public static function unifyFilterParams(array $params): array
    {
        if (isset($params['count_paging'])) {
            $params['page_count'] = $params['count_paging'];
            unset($params['count_paging']);
        }

        if (isset($params['orderby'])) {
            $params['order_by'] = $params['orderby'];
            unset($params['orderby']);
        }

        if (!isset($params['current_page']) && isset($params['page'])) {
            $params['current_page'] = $params['page'];
            unset($params['page']);
        }

        if (!isset($params['no_limit']) && isset($params['nolimit'])) {
            $params['no_limit'] = $params['nolimit'];
            unset($params['nolimit']);
        }

        return $params;
    }

    public static function applySelectLogic(Builder $query, string $table, array $params): Builder
    {
        if (isset($params['fields']) && $params['fields'] != false) {
            $isFields = is_string($params['fields']) ? explode(',', $params['fields']) : $params['fields'];

            $isFieldsQ = [];
            foreach ($isFields as $isField) {
                if (is_string($isField)) {
                    $isField = trim($isField);
                    if ($isField !== '') {
                        $isFieldsQ[] = $table . '.' . $isField;
                    }
                }
            }
            if ($isFieldsQ) {
                $query->select($isFieldsQ);
            }
        } else {
            $query->select($table . '.*');
        }

        return $query;
    }

    public static function applyLimitLogic(Builder $query, array $params): Builder
    {
        $limit = static::$defaultLimit;
        $no_limit = isset($params['no_limit']);

        if (!isset($params['page_count'])) {
            if (!$no_limit) {
                if (isset($params['limit']) && ($params['limit'] === 'nolimit' || $params['limit'] === 'no_limit')) {
                    $no_limit = true;
                    unset($params['limit']);
                }

                if (isset($params['limit']) && $params['limit']) {
                    $limit = intval($params['limit']);
                }
            }

            if (!$no_limit) {
                $query->limit($limit);

                if (isset($params['paging_param']) && $params['paging_param']) {
                    if (isset($params[$params['paging_param']]) && $params[$params['paging_param']]) {
                        $params['current_page'] = $params[$params['paging_param']];
                    }
                }

                if (isset($params['current_page']) && $params['current_page']) {
                    $currentPageValue = intval($params['current_page']);
                    if ($currentPageValue > 1) {
                        $criteria = intval($currentPageValue - 1) * intval($limit);
                        $query->skip($criteria);
                    }
                }
            }
        }

        return $query;
    }

    public static function applyOrderByLogic(Builder $query, array $params): Builder
    {
        if (isset($params['order_by']) && is_string($params['order_by'])) {
            $orderBy = trim($params['order_by']);
            $orderByCriteria = explode(',', $orderBy);
            foreach ($orderByCriteria as $c) {
                $c = urldecode($c);
                $c = explode(' ', $c);
                if (isset($c[0]) && trim($c[0]) !== '') {
                    $c[0] = trim($c[0]);
                    if (isset($c[1])) {
                        $c[1] = trim($c[1]);
                    }
                    if (isset($c[1]) && $c[1] !== '') {
                        $query->orderBy($c[0], $c[1]);
                    } elseif (isset($c[0])) {
                        $query->orderBy($c[0]);
                    }
                }
            }
        }

        return $query;
    }

    public static function applyGroupByLogic(Builder $query, array $params): Builder
    {
        if (isset($params['group_by']) && is_string($params['group_by'])) {
            $groupByCriteria = explode(',', $params['group_by']);
            if (!empty($groupByCriteria)) {
                $groupByCriteria = array_map('trim', $groupByCriteria);
            }
            if (!empty($groupByCriteria)) {
                $query->groupBy($groupByCriteria);
            }
        }

        return $query;
    }

    public static function applyExcludeIdsLogic(Builder|\Illuminate\Database\Query\Builder $query, string $table, array $params): Builder|\Illuminate\Database\Query\Builder
    {
        $excludeIds = [];
        if (isset($params['exclude_ids']) && is_string($params['exclude_ids'])) {
            $excludeIdsMerge = explode(',', $params['exclude_ids']);
            if ($excludeIdsMerge) {
                $excludeIds = array_merge($excludeIds, $excludeIdsMerge);
            }
        } elseif (isset($params['exclude_ids']) && is_array($params['exclude_ids'])) {
            $excludeIds = array_merge($excludeIds, $params['exclude_ids']);
        }
        if (!empty($excludeIds)) {
            $query->whereNotIn($table . '.id', $excludeIds);
        }

        return $query;
    }

    public static function applyIncludeIdsLogic(Builder|\Illuminate\Database\Query\Builder $query, string $table, array $params): Builder|\Illuminate\Database\Query\Builder
    {
        $includeIds = [];
        if (isset($params['ids']) && is_string($params['ids'])) {
            $idsMerge = explode(',', $params['ids']);
            if ($idsMerge) {
                $includeIds = array_merge($includeIds, $idsMerge);
            }
        } elseif (isset($params['ids']) && is_array($params['ids'])) {
            $includeIds = array_merge($includeIds, $params['ids']);
        }
        if (!empty($includeIds)) {
            $query->whereIn($table . '.id', $includeIds);
        }

        return $query;
    }

    public static function applyKeywordLogic(Builder $query, array $params): Builder
    {
        if (isset($params['keyword'])) {
            $query->filter(['keyword' => $params['keyword']]);
        }

        return $query;
    }

    public static function applyTagsLogic(Builder $query, array $params): Builder
    {
        if (isset($params['tags'])) {
            $query->filter(['tags' => $params['tags']]);
        }

        if (isset($params['tag_names'])) {
            $query->filter(['tags' => $params['tag_names']]);
        }

        if (isset($params['all_tags'])) {
            $query->filter(['allTags' => $params['all_tags']]);
        }

        return $query;
    }

    public static function applyClosureLogic(Builder $query, array $params): Builder
    {
        foreach ($params as $paramKey => $paramValue) {
            if (is_object($paramValue) && ($paramValue instanceof Closure)) {
                $query = call_user_func($paramValue, $query, $params);
            }
        }

        return $query;
    }
}
