<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByKeywordTrait
{
    public function keyword($keyword)
    {
        $model = $this->getModel();
        $searchInFields = $model->getFillable();
        $guardedFields = $model->getGuarded();
        $tableFields = $model->getConnection()->getSchemaBuilder()->getColumnListing($this->getModel()->getTable());

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

        return $this->query->where(function ($query) use ($searchInFields, $keyword) {
            if ($searchInFields) {
                foreach ($searchInFields as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $keyword . '%');
                }
            }
        });
    }

}