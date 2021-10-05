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
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
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

            $this->query->where(function ($subQuerySearch) use ($table, $searchInFields, $keywordToSearch) {
                if ($searchInFields) {
                    foreach ($searchInFields as $field) {
                        $subQuerySearch->orWhere($table . '.' . $field, 'LIKE', '%' . $keywordToSearch . '%');
                    }
                }

                if (MultilanguageHelpers::multilanguageIsEnabled()) {
                    $subQuerySearch->orWhereHas('translations', function ($query) use ($table, $keywordToSearch) {
                     //   dump($keywordToSearch);
                        $query->where('rel_type', $table);
                        $query->where('field_value', 'LIKE', '%' . $keywordToSearch . '%');
                    });
                }

                return $subQuerySearch;
            });

        }
        return $this->query;
    }

}
