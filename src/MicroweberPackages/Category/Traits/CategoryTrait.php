<?php

namespace MicroweberPackages\Category\Traits;

use MicroweberPackages\Category\Models\CategoryItem;

trait CategoryTrait {

    public $addContentToCategory = [];

    public function addToCategory($contentId)
    {
        $this->addContentToCategory[] = $contentId;
    }

    public static function bootCategoryTrait()
    {
        static::saving(function ($model)  {
            // append content to categories
            $model->addContentToCategory = $model->categories;
            unset($model->categories);
        });

        static::saved(function($model) {
            $model->setCategories($model->addContentToCategory);
        });
    }

    public function setCategories($categoryIds) {

        if (is_string($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
        }

        $entityCategories = CategoryItem::where('rel_id', $this->id)->where('rel_type',$this->getMorphClass())->get();
        if ($entityCategories) {
            foreach ($entityCategories as $entityCategory) {
                if (!in_array($entityCategory->parent_id, $categoryIds)) {
                    $entityCategory->delete();
                }
            }
        }

        if (!empty($categoryIds)) {
            foreach ($categoryIds as $categoryId) {

                $categoryItem = CategoryItem::where('rel_id', $this->id)->where('rel_type',$this->getMorphClass())->where('parent_id', $categoryId)->first();
                if (!$categoryItem) {
                    $categoryItem = new CategoryItem();
                }

                $categoryItem->rel_id = $this->id;
                $categoryItem->rel_type = $this->getMorphClass();
                $categoryItem->parent_id = $categoryId;
                $categoryItem->save();
            }
        }

    }

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
        $this->fillable[] = 'categories';
    }

}