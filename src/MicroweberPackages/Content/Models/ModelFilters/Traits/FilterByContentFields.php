<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByContentFields
{
    public function contentFields($params)
    {
        if (empty($params)) {
            return;
        }

        if (is_string($params)) {
            $params = parse_params($params);
        }

        foreach ($params as $key => $value) {

            if (empty(trim($value))) {
                continue;
            }

            $this->query->whereHas('contentField', function (Builder $query) use ($key, $value) {
                $query->where('field', '=', $key);
                if (is_array($value)) {
                    $query->where(function ($query) use ($value) {
                        foreach ($value as $v){
                              $query->orWhere('value', '=', $v);
                        }
                    });
                } else {
                    $query->where('value', '=', $value);
                }
            });
        }

        return $this->query;

    }
}
