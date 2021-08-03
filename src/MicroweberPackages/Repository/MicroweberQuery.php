<?php
namespace MicroweberPackages\Repository;

class MicroweberQuery {


    public static function execute($model, $params) {

        $table = $model->getModel()->getTable();
        $columns  = \Schema::getColumnListing($table);
        
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

        if (isset($params['limit']) and ($params['limit'] == 'nolimit' or $params['limit'] == 'no_limit')) {
            unset($params['limit']);
        }

        if (isset($params['limit']) and $params['limit']) {
            $model->limit($params['limit']);
        }


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

        if (isset($params['single'])) {
            $result = $model->first();
        } else {
            $result = $model->get();
        }

       // dd($params, $result);

        return $result;
    }

}
