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
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

trait FilterByKeywordTrait
{
    public function keyword($keyword)
    {
        $model = $this->getModel();
        $table = $model->getTable();
      //  $searchInFields = $model->getSearchable();
        $searchInFields = $model->getSearchableByKeyword();
        $keywordToSearch = false;

        $xssClean = new XSSClean();

        if (is_string($keyword)) {
            $keyword = $xssClean->clean($keyword);
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
                    $antiXss = new \MicroweberPackages\Helper\HTMLClean();
                    $keywordToSearch = $antiXss->clean($keywordToSearch);
                    $keywordToSearch = mb_trim($keywordToSearch);
                    if ($keywordToSearch != '') {
                        if (mb_strlen($keywordToSearch) > 1000) {
                            $keywordToSearch = mb_substr($keywordToSearch, 0, 1000);
                        }
                        foreach ($searchInFields as $field) {
                           // $subQuerySearch->orWhere($table . '.' . $field, '=',  $keywordToSearch);
                            $subQuerySearch->orWhere($table . '.' . $field, 'LIKE', '%' . $keywordToSearch . '%');
                           // $subQuerySearch->whereRaw("LOWERCASE(  $field') LIKE '%'". strtolower($keywordToSearch)."'%'");

                        }
                    }
                }
                return $subQuerySearch;
            });


            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $this->query->orWhere(function ($subQuerySearch) use ($table, $searchInFields, $keywordToSearch) {
                    $multilanguageTranslationsQuery = MultilanguageTranslations::query();
                    $multilanguageTranslationsQuery->where('rel_type', $table);
                    $multilanguageTranslationsQuery->whereIn('field_name', ['url', 'description', 'title']);
                    $multilanguageTranslationsQuery->where('field_value', 'LIKE', '%' . $keywordToSearch . '%');
                    $multilanguageTranslationsQuery->limit(3000); // MYSQL LIMIT FOR WHERE IN
                    $multilanguageTranslations = $multilanguageTranslationsQuery->get();
                    $relIds = $multilanguageTranslations->pluck('rel_id');
                    if (!empty($relIds)) {
                      return  $subQuerySearch->orWhereIn($table.'.id', $relIds);
                    }
                });
            }

            return $this->query;
        }
    }

}
