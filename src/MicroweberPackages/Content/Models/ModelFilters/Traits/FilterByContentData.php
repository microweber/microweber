<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByContentData
{
    public function contentData($params)
    {
        if (empty($params)) {
            return;
        }

        if (is_string($params)) {
            $params = parse_params($params);

        }
        foreach ($params as $key => $value) {
            $this->query->whereHas('contentData', function (Builder $query) use ($key, $value) {
                $query->where('field_name', '=', $key);
                $query->where('field_value', '=', $value);
            });
        }
        return $this->query;

    }
}