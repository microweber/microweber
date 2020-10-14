<?php

namespace MicroweberPackages\Category\Traits;

use MicroweberPackages\Category\Models\CategoryItem;

trait CategoryTrait {

  /*  public $addContentToCategory = [];

    public function addToCategory($contentId)
    {
        $this->addContentToCategory[] = $contentId;
    }

    public static function bootCategoryTrait()
    {
        static::saved(function ($model)  {
            // append content to categories
        });
    }*/

    public function category()
    {
        return $this->hasMany(CategoryItem::class, 'rel_id');
    }

    public function getCategoryAttribute()
    {
        return $this->category()->get();
    }

    public function initializeCategoryTrait()
    {
        $this->appends[] = 'category';
    }
}