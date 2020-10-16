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
        $searchInFields = $this->getModel()->fillable;

        if (isset($this->input['searchInFields'])) {
            if (strpos($this->input['searchInFields'], ',') !== false) {
               $searchInFields = explode(',', $this->input['searchInFields']);
            }
        }

        return $this->query->where(function ($query) use ($searchInFields, $keyword) {
            foreach ($searchInFields as $field) {
                $query->orWhere($field, 'LIKE', '%'.$keyword.'%');
            }
        });
    }

}