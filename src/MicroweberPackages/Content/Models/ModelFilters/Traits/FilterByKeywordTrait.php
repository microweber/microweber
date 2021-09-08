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
use voku\helper\AntiXSS;

trait FilterByKeywordTrait
{
    public function keyword($keyword)
    {
        $model = $this->getModel();
        $table = $model->getTable();
        $searchInFields = $model->getSearchable();
        $keywordToSearch = false;
        $antixss = new AntiXSS();
        if (is_string($keyword)) {
            $keyword = $antixss->xss_clean($keyword);
            if ($keyword) {
                $keywordToSearch = $keyword;
            }
        }



        if ($keywordToSearch) {

            if (isset($this->input['searchInFields'])) {
                $searchInFieldsNew = [];
                $searchInFieldsInput = $this->input['searchInFields'];
                if (strpos($this->input['searchInFields'], ',') !== false) {
                    $searchInFieldsInputArr = explode(',', $this->input['searchInFields']);
                }
                if (is_string($searchInFieldsInput)) {
                    $searchInFieldsInputArr = array($searchInFieldsInput);
                }
                if ($searchInFieldsInputArr) {
                    $searchInFieldsInputArr = array_map('trim', $searchInFieldsInputArr);
                    if ($searchInFieldsInputArr) {
                        foreach ($searchInFieldsInputArr as $item) {
                            if (in_array($item, $searchInFields)) {
                                $searchInFieldsNew[] = $item;
                            }
                        }
                    }
                }

                if (!empty($searchInFieldsNew)) {
                    $searchInFields = $searchInFieldsNew;
                }
            }

            $this->query->where(function ($query2) use ($table, $searchInFields, $keywordToSearch) {
                if ($searchInFields) {
                    foreach ($searchInFields as $field) {
                        $query2->orWhere($table . '.' . $field, 'LIKE', '%' . $keywordToSearch . '%');
                    }
                    return $query2;
                }
            });

        }
        return $this->query;
    }

}
