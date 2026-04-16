<?php

namespace Modules\CustomFields\Repositories;

use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\CustomFields\Models\CustomField;

/**
 * @mixin AbstractRepository
 */
class CustomFieldRepository extends AbstractRepository
{

    /**
     * Specify Models class name
     *
     * @return string
     */
    public $model = CustomField::class;

    public function get($params)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {
            return CustomField::getWithValues($params);
        });
    }


}
