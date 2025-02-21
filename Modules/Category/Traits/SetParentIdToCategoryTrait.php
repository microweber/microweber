<?php

namespace Modules\Category\Traits;

use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryItem;

trait SetParentIdToCategoryTrait
{



    public function initializeSetParentIdToCategoryTrait()
    {

    }



    public static function bootSetParentIdToCategoryTrait()
    {
        static::saving(function ($model) {
            $attributes = $model->getAttributes();
            if($attributes and array_key_exists('parent_id', $attributes) &&  is_null($model->parent_id)){
                $model->parent_id = 0;

            }


        });

    }

}
