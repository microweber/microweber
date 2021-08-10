<?php
namespace MicroweberPackages\Repository;

use MicroweberPackages\Database\Traits\QueryFilter;

class MicroweberQuery {

    use QueryFilter;


    public static function execute($model, $params) {

        $table = $model->getModel()->getTable();
        $columns  = $model->getModel()->getFillable();

        if (is_string($params)) {
            $params = parse_params($params);
        }

      //  $model = app()->database_manager->map_filters($model,$params,$table);

        $model = self::_selectLogic($model, $table, $columns, $params);
        $model = self::_closureLogic($model, $table, $columns, $params);
        $model = self::_limitLogic($model, $table, $columns, $params);

        $whereParams = [];
        foreach ($params as $paramKey=>$paramValue) {
            if (in_array($paramKey, $columns)) {
                $whereParams[$paramKey] = $paramValue;
            }
        }

        if (is_array($whereParams) and !empty($whereParams)) {
            foreach ($whereParams as $k => $v) {
                $model->where($table . '.' . $k, '=', $v);
            }
        }

        if (isset($params['count']) and $params['count']) {
            $exec = $model->count();
        } else if (isset($params['single']) || isset($params['one'])) {
            // $model->limit(1);
            $exec = $model->first();
        } else {
            $exec = $model->get();
        }

       // dd($params, $result);

        $result = [];
        if ($exec != null) {
            if (is_numeric($exec)) {
                $result = $exec;
            } else {
                $result = $exec->toArray();
            }
        }

        return $result;
    }


    public static function _limitLogic($model, $table, $columns, $params) {

        if (isset($params['limit']) and ($params['limit'] == 'nolimit' or $params['limit'] == 'no_limit')) {
            unset($params['limit']);
        }

        if (isset($params['limit']) and $params['limit']) {
            $model->limit($params['limit']);
        }

        return $model;
    }

    public static function _closureLogic($model, $table, $columns, $params) {

        foreach ($params as $paramKey=>$paramValue) {
            if (is_object($params[$paramKey]) && ($params[$paramKey] instanceof \Closure)) {
                $model = call_user_func($params[$paramKey], $model, $params);
            }
        }

        return $model;
    }

    public static function _selectLogic($model, $table, $columns, $params) {
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
        }
        return $model;
    }

}
