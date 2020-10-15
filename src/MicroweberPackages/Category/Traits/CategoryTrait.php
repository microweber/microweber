<?php

namespace MicroweberPackages\Category\Traits;

use MicroweberPackages\Category\Models\CategoryItem;

trait CategoryTrait {

    private $_addContentToCategory = [];

    public function initializeCategoryTrait()
    {
        //$this->appends[] = 'categories';
        $this->fillable[] = 'category_ids';
    }

   /* public function addToCategory($contentId)
    {
        $this->_addContentToCategory[] = $contentId;
    }*/

    public static function bootCategoryTrait()
    {
        static::saving(function ($model)  {
            // append content to categories
            $model->_addContentToCategory = $model->category_ids;
            unset($model->category_ids);
        });

        static::saved(function($model) {
            $model->_setCategories($model->_addContentToCategory);
        });
    }

    private function _setCategories($categoryIds) {

        if (!is_array($categoryIds)) {
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

    public function categoryItems()
    {
        return $this->hasMany(CategoryItem::class, 'rel_id');
    }

    public function getCategoriesAttribute()
    {
        $categories = [];

        foreach($this->categoryItems()->with('parent')->get() as $category) {
            $categories[] = $category->parent;
        }

        return collect($categories);
    }

}