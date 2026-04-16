<?php

namespace Modules\CustomFields\Repositories;

use MicroweberPackages\Repository\Repositories\CachingModelRepository;
use Modules\CustomFields\Models\CustomField;

class CustomFieldRepository extends CachingModelRepository
{

    protected string $modelClass = CustomField::class;

    public function get($params)
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () use ($params) {
            return CustomField::getWithValues($params);
        });
    }


}
