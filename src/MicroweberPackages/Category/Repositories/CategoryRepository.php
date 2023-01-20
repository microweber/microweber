<?php

namespace MicroweberPackages\Category\Repositories;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Repository\MicroweberQuery;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use function Symfony\Component\Translation\t;

class CategoryRepository extends AbstractRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Category::class;


    public function getByUrl($url)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($url) {

            $getCategory = \DB::table('categories')->where('url', $url)->get();
            $getCategory = collect($getCategory)->map(function ($item) {
                return (array)$item;
            })->toArray();

            return $getCategory;
        });

    }

    public function getByColumnNameAndColumnValue($columnName, $columnValue)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($columnName, $columnValue) {

            $getCategory = \DB::table('categories')->where($columnName, $columnValue)->first();
            if ($getCategory != null) {

                $getCategory = (array)$getCategory;

                $hookParams = [];
                $hookParams['data'] = $getCategory;
                $hookParams['hook_overwrite_type'] = 'single';
                $overwrite = app()->event_manager->response(get_class($this) . '\\getByColumnNameAndColumnValue', $hookParams);

                if (isset($overwrite['data'])) {
                    $getCategory = $overwrite['data'];
                }

                return $getCategory;

            } else {
                return false;
            }
        });
    }


    /**
     * Find category media by category id.
     *
     * @param mixed $id
     *
     * @return array
     */
    public function getMedia($id)
    {

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $item = $this->findById($id);
            if ($item) {
                $get = $item->media;
                if ($get) {
                    return $get->toArray();
                }
            }
            return [];

        });
    }

    public function tree()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $getCategory = \DB::table('categories')->where('data_type', 'category')->where('parent_id',0);
            $getCategory = $getCategory->get();

            if ($getCategory != null) {

                $getCategory = collect($getCategory)->map(function ($item) {

                    $item->childs = $this->getCategoryChildsTree($item->id);

                    return (array)$item;
                })->toArray();

                return $getCategory;
            }

            return false;
        });
    }

    public function getCategoryChildsTree($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {

            $getCategory = \DB::table('categories')->where('data_type', 'category');

            if (is_array($categoryId)) {
                $getCategory->whereIn('parent_id', $categoryId);
            } else {
                $getCategory->where('parent_id', $categoryId);
            }

            $getCategory = $getCategory->get();

            if ($getCategory != null) {

                $getCategory = collect($getCategory)->map(function ($item) {

                    $item->childs = $this->getCategoryChildsTree($item->id);

                    return (array)$item;
                })->toArray();


                return $getCategory;

            }

            return false;
        });
    }

    /**
     *
     * @param mixed $categoryId
     *
     * @return boolean|array
     */
    public function getSubCategories($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {

            $getCategory = \DB::table('categories')
                ->select(['id', 'parent_id'])
                ->where('data_type', 'category');

            if (is_array($categoryId)) {
                $getCategory->whereIn('parent_id', $categoryId);
            } else {
                $getCategory->where('parent_id', $categoryId);
            }

            $getCategory = $getCategory->get();

            if ($getCategory != null) {


                $getCategory = collect($getCategory)->map(function ($item) {
                    return (array)$item;
                })->toArray();


                return $getCategory;

            }

            return false;
        });
    }

    /**
     * Check if category has products in stock.
     *
     * @param mixed $categoryId
     *
     * @return boolean
     */
    public function hasProductsInStock($categoryId)
    {

        $count = $this->getCategoryProductsInStockCount($categoryId);
        if ($count > 0) {
            return true;
        }

        $getChildrens = $this->getCategoryChildsTree($categoryId);

        $productsInStock = $this->_checkProductsInStockRecursive($getChildrens);
        if ($productsInStock) {
            return true;
        }

        return false;
    }

    private function _checkProductsInStockRecursive($categories)
    {
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $count = $this->getCategoryProductsInStockCount($category['id']);
                if ($count > 0) {
                    return true;
                }
                if (isset($category['childs']) && !empty($category['childs'])) {
                    return $this->_checkProductsInStockRecursive($category['childs']);
                }
            }
        }
    }

    public function getCategoryItemsCountAll()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $categoryItemsCountGroupedByRelType = [];
            $categoryItemsCountData = $this->getCategoryItemsCountQueryBuilder()
                ->get();

            if ($categoryItemsCountData) {
                foreach ($categoryItemsCountData as $key => $value) {
                    $categoryItemsCountGroupedByRelType[$value->parent_id] = $value->count;
                }
            }
            return $categoryItemsCountGroupedByRelType;
        });

    }

    public function getCategoryItemsInStockCountAll()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $categoryItemsCountGroupedByRelType = [];
            $query = $this->getCategoryItemsCountQueryBuilder();
            $query->whereIn('categories_items.rel_id',
                Product::select(['content.id'])
                    ->filter(['inStock' => 1])
                    ->select(['content.id'])
            );

            $categoryItemsCountData = $query->get();
            if ($categoryItemsCountData) {
                foreach ($categoryItemsCountData as $key => $value) {
                    $categoryItemsCountGroupedByRelType[$value->parent_id] = $value->count;
                }
            }
            return $categoryItemsCountGroupedByRelType;
        });

    }

    public function getCategoryContentItemsCount($categoryId)
    {
        $categoryItemsCountGroupedByRelType = $this->getCategoryItemsCountAll();

        if (isset($categoryItemsCountGroupedByRelType) and isset($categoryItemsCountGroupedByRelType[$categoryId])) {
            return $categoryItemsCountGroupedByRelType[$categoryId];
        }

        return 0;
    }

    public function getCategoryProductsInStockCount($categoryId)
    {
        $categoryItemsCountGroupedByRelType = $this->getCategoryItemsInStockCountAll();

        if (isset($categoryItemsCountGroupedByRelType) and isset($categoryItemsCountGroupedByRelType[$categoryId])) {
            return $categoryItemsCountGroupedByRelType[$categoryId];
        }

        return 0;
     }


    private function getCategoryItemsCountQueryBuilder()
    {

        $realTableName = app()->database_manager->real_table_name('content');
        $model = (new CategoryItem())->newQuery();
        $model->leftJoin('content', function ($join) {
            $join->on('content.id', '=', 'categories_items.rel_id')
                ->where('content.is_deleted', '=', 0)
                ->where('content.is_active', '=', 1);
        })
            ->select(['categories_items.parent_id', 'categories_items.rel_type', DB::raw('count( DISTINCT `'.$realTableName.'`.`id` ) as count')])
            ->where('categories_items.rel_type', 'content')
            ->groupBy('categories_items.parent_id');


        return $model;

    }

}
