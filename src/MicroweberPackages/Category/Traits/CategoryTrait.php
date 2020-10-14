<?php

namespace MicroweberPackages\Category\Traits;

use MicroweberPackages\Category\Models\CategoryItem;

trait CategoryTrait {

    private $_addContentToCategory = [];

    public function addToCategory($contentId)
    {
        $this->_addContentToCategory[] = $contentId;
    }

    public static function bootCategoryTrait()
    {
        static::saving(function ($model)  {
            // append content to categories
            $model->_addContentToCategory = $model->categories;
            unset($model->categories);
        });

        static::saved(function($model) {
            $model->setCategories($model->_addContentToCategory);
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

    public function categories()
    {
        return $this->hasMany(CategoryItem::class, 'rel_id');
    }

    public function getCategoriesAttribute()
    {
        return $this->categories()->get();
    }

    public function initializeCategoryTrait()
    {
        $this->appends[] = 'categories';
        $this->fillable[] = 'categories';
    }

}