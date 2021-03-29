<?php

namespace MicroweberPackages\Category\Traits;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;

trait CategoryTrait
{

    private $_addContentToCategory = null;
    private $_removeFromAllCategories = false;

    public function initializeCategoryTrait()
    {
        $this->appends[] = 'categories';
        //	$this->with[] = 'categoryItems';
        $this->fillable[] = 'category_ids';
    }

    /* public function addToCategory($contentId)
     {
         $this->_addContentToCategory[] = $contentId;
     }*/

    public static function bootCategoryTrait()
    {
        static::saving(function ($model) {
            // append content to categories
            if (isset($model->category_ids)) {
                $model->_addContentToCategory = $model->category_ids;
            }
            unset($model->category_ids);
        });

        static::saved(function ($model) {
            if (isset($model->_addContentToCategory)) {
                $model->_setCategories($model->_addContentToCategory);
            }
        });
    }

    private function _setCategories($categoryIds)
    {

        if (!is_array($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
        }

        //delete from categories if 1st category is 0
        if (reset($categoryIds) == 0) {
            CategoryItem::where('rel_id', $this->id)->where('rel_type', $this->getMorphClass())->delete();
            return;
        }

        $entityCategories = CategoryItem::where('rel_id', $this->id)->where('rel_type', $this->getMorphClass())->get();
        if ($entityCategories) {
            foreach ($entityCategories as $entityCategory) {
                if (!in_array($entityCategory->parent_id, $categoryIds)) {
                    $entityCategory->delete();
                }
            }
        }

        if (!empty($categoryIds)) {
            foreach ($categoryIds as $categoryId) {

                $categoryItem = CategoryItem::where('rel_id', $this->id)->where('rel_type', $this->getMorphClass())->where('parent_id', $categoryId)->first();
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


//    public function categories()
//    {
//        return $this->hasMany(Category::class, 'rel_id');
//    }
//



    public function categoryItems()
    {
        return $this->hasMany(CategoryItem::class, 'rel_id');
    }

    public function getCategoriesAttribute()
    {
        $categories = [];

        foreach ($this->categoryItems()->with('parent')->get() as $category) {
            $categories[] = $category;
        }

        return collect($categories);
    }

}