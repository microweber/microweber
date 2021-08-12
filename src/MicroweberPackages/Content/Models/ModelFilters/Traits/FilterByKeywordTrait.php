<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

trait FilterByKeywordTrait
{
    public function keyword($keyword)
    {
        $dbDriver = Config::get('database.default');
        $table = $this->getModel()->getTable();
        $model = $this->getModel();
        $searchInFields = $model->getFillable();
        $guardedFields = $model->getGuarded();
        $tableFields = $model->getConnection()->getSchemaBuilder()->getColumnListing($table);

        if (isset($this->input['searchInFields'])) {
            if (strpos($this->input['searchInFields'], ',') !== false) {
                $searchInFields = explode(',', $this->input['searchInFields']);
            }
        }

        if ($searchInFields and $tableFields) {
            $searchInFields = array_diff($tableFields, $searchInFields);
        }

        if ($searchInFields and $guardedFields) {
            $searchInFields = array_diff($searchInFields, $guardedFields);
        }

        /*$this->query->where(function ($query) use ($table, $searchInFields, $keyword) {
            if ($searchInFields) {
                foreach ($searchInFields as $field) {
                    $query->orWhere($table .'.'. $field, 'LIKE', '%' . $keyword . '%');
                }
            }
        });*/

        $query = $this->query;

        if ($searchInFields) {
            $to_search_in_fields = $searchInFields;

            $to_search_keyword = $keyword;

            if ($to_search_in_fields != false and $to_search_keyword != false) {
                if ($dbDriver == 'sqlite') {
                    $pdo = DB::connection('sqlite')->getPdo();
                    $pdo->sqliteCreateFunction('regexp',
                        function ($pattern, $data, $delimiter = '~', $modifiers = 'isuS') {
                            if (isset($pattern, $data) === true) {
                                return preg_match(sprintf('%1$s%2$s%1$s%3$s', $delimiter, $pattern, $modifiers), $data) > 0;
                            }

                            return;
                        }
                    );




                }


                $to_search_keywords = explode(',', $to_search_keyword);

                if (!empty($to_search_keywords)) {
                    $kw_number_counter = 0;
                    foreach ($to_search_keywords as $to_search_keyword) {
                        $to_search_keyword = trim($to_search_keyword);


                        if ($to_search_keyword != false) {
                            if (is_string($to_search_in_fields)) {
                                $to_search_in_fields = explode(',', $to_search_in_fields);
                            }
                            $to_search_keyword = preg_replace("/(^\s+)|(\s+$)/us", '', $to_search_keyword);
                            $to_search_keyword = strip_tags($to_search_keyword);
                            $to_search_keyword = str_replace('\\', '', $to_search_keyword);
                            $to_search_keyword = str_replace('*', '', $to_search_keyword);
                            $to_search_keyword = str_replace('[', '', $to_search_keyword);
                            $to_search_keyword = str_replace(']', '', $to_search_keyword);
                            $to_search_keyword = str_replace(';', '', $to_search_keyword);
                            if ($to_search_keyword != '') {

                                /*                                            // Search in tags
                                                                           if ($this->supports($table, 'tag') and isset($filter['search_in_tags']) && $filter['search_in_tags']) {
                                                                                 $query = $query->join('tagging_tagged', 'tagging_tagged.taggable_id', '=', $table . '.id')->distinct();
                                                                                if ($dbDriver == 'pgsql') {
                                                                                    $query = $query->orWhere('tagging_tagged.tag_name', 'ILIKE', '%'.$to_search_keyword.'%');
                                                                                } else {
                                                                                    $query = $query->orWhere('tagging_tagged.tag_name', 'LIKE', '%'.$to_search_keyword.'%');
                                                                                }
                                                                            }*/

                                if (!empty($to_search_in_fields)) {

                                    $where_or_where = 'orWhere';

                                    //check search in joined table
                                    $search_joined_tables_check = array();
                                    foreach ($to_search_in_fields as $to_search_in_field) {
                                        $check_if_join_is_needed = explode('.', $to_search_in_field);
                                        if (isset($check_if_join_is_needed[1])) {
                                            $rest = $check_if_join_is_needed[0];
                                            if (!isset($search_joined_tables_check[$rest])) {
                                                $query = $query->join($rest, $rest . '.rel_id', '=', $table . '.id')
                                                    ->where($rest . '.rel_type', $table)->distinct();
                                                $search_joined_tables_check[$rest] = true;
                                            }
                                        }
                                    }

                                    //$query->{$where_or_where}(function ($query) use ($to_search_in_fields, $to_search_keyword, $params, $table, $dbDriver,$where_or_where) {
                                    $query =  $query->$where_or_where(function ($query) use ($to_search_in_fields, $to_search_keyword, $table, $dbDriver,$where_or_where) {


                                        foreach ($to_search_in_fields as $to_search_in_field) {
                                            $to_search_keyword = str_replace('.', ' ', $to_search_keyword);
                                            if ($dbDriver == 'pgsql') {
                                                $query = $query->$where_or_where($to_search_in_field, 'ILIKE', '%' . $to_search_keyword . '%');
                                            } else {
                                                $query = $query->orWhere($to_search_in_field, 'LIKE', '%' . $to_search_keyword . '%');
                                                $query = $query->orWhere($to_search_in_field, 'REGEXP', $to_search_keyword);
                                                $query = $query->orWhere($to_search_in_field, 'LIKE', '%' . $to_search_keyword);
                                                $query = $query->orWhere($to_search_in_field, 'LIKE', '.' . $to_search_keyword);
                                                $query = $query->orWhere($to_search_in_field, 'LIKE', $to_search_keyword . '%');
                                                if($where_or_where == 'orWhere'){
                                                    $query = $query->orWhereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);
                                                } else {
                                                    $query = $query->orWhereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);

                                                    // $query = $query->whereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);
                                                    // $query = $query->orWhereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);

                                                }
                                            }
                                        }




                                    });


                                    $query = $query->distinct();




                                }
                            }
                        }
                        $kw_number_counter++;
                    }


                }

            }
        }

        return $query;
    }

}
