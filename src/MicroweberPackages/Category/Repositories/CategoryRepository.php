<?php

namespace MicroweberPackages\Category\Repositories;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryItem;
use Modules\Product\Models\Product;

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

            $getCategory = DB::table('categories')->where('url', $url)->get();
            $getCategory = collect($getCategory)->map(function ($item) {
                return (array)$item;
            })->toArray();

            return $getCategory;
        });

    }

    public function getByColumnNameAndColumnValue($columnName, $columnValue)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($columnName, $columnValue) {

            $getCategory = DB::table('categories')->where($columnName, $columnValue)->first();
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

            $getCategory = DB::table('categories')->where('data_type', 'category')->where('parent_id', 0);
            $getCategory = $getCategory->get();

            if ($getCategory != null) {

                $getCategory = collect($getCategory)->map(function ($item) {

                    $item->childs = $this->getChildsTree($item->id);

                    return (array)$item;
                })->toArray();

                return $getCategory;
            }

            return false;
        });
    }

    public function getChildsTree($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {

            $getCategory = DB::table('categories')->where('data_type', 'category');

            if (is_array($categoryId)) {
                $getCategory->whereIn('parent_id', $categoryId);
            } else {
                $getCategory->where('parent_id', $categoryId);
            }

            $getCategory = $getCategory->get();

            if ($getCategory != null) {

                $getCategory = collect($getCategory)->map(function ($item) {

                    $item->childs = $this->getChildsTree($item->id);

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

            $getCategory = DB::table('categories')
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

        $count = $this->getProductsInStockCount($categoryId);
        if ($count > 0) {
            return true;
        }

        $getChildrens = $this->getChildsTree($categoryId);

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
                $count = $this->getProductsInStockCount($category['id']);
                if ($count > 0) {
                    return true;
                }
                if (isset($category['childs']) && !empty($category['childs'])) {
                    return $this->_checkProductsInStockRecursive($category['childs']);
                }
            }
        }
    }

    public function getItemsCountAll()
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

    public function getItemsInStockCountAll()
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

    public function getItemsCount($categoryId)
    {
        $categoryItemsCountGroupedByRelType = $this->getItemsCountAll();

        if (isset($categoryItemsCountGroupedByRelType) and isset($categoryItemsCountGroupedByRelType[$categoryId])) {
            return $categoryItemsCountGroupedByRelType[$categoryId];
        }

        return 0;
    }


    public function getProductsInStockCount($categoryId)
    {
        $categoryItemsCountGroupedByRelType = $this->getItemsInStockCountAll();

        if (isset($categoryItemsCountGroupedByRelType) and isset($categoryItemsCountGroupedByRelType[$categoryId])) {
            return $categoryItemsCountGroupedByRelType[$categoryId];
        }

        return 0;
    }

    public function getItems($categoryId, $relType = false, $relId = false)
    {

        if($relType == 'content' || $relType == 'category'){
            $relType = app()->database_manager->morphClassFromTable($relType);
        }

        if(!$relType){
            $relType = morph_name(\Modules\Content\Models\Content::class);
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId, $relType, $relId) {
            $model = (new CategoryItem())->newQuery();
            if ($categoryId) {
                $model->where('parent_id', $categoryId);
            }
            if ($relType) {
                $model->where('rel_type', $relType);
            }
            if ($relId) {
                $model->where('rel_id', $relId);
            }
            $data = $model->get();

            if ($data and $data->count() > 0) {
                return $data->toArray();
            } else {
                return false;
            }
        });
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
            ->select(['categories_items.parent_id', 'categories_items.rel_type', DB::raw('count( DISTINCT `' . $realTableName . '`.`id` ) as count')])
            ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))->groupBy('categories_items.parent_id');


        return $model;

    }

    public function save($data)
    {
        app()->category_manager->useCache = false;
        cache_clear('options');
        cache_clear('content');
        cache_clear('repositories');
        cache_clear('pages');
        cache_clear('categories');

        if (isset($data['parent']) and !isset($data['parent_id'])) {
            $data['parent_id'] = $data['parent'];
        }

        if (isset($data['rel_type']) and $data['rel_type'] == 'content') {
            $data['rel_type'] = morph_name(\Modules\Content\Models\Content::class);

        }
        if (isset($data['rel_type']) and $data['rel_type'] == 'category') {
            $data['rel_type'] = morph_name(\Modules\Category\Models\Category::class);
        }

        if (isset($data['parent_page'])) {
            $data['rel_type'] = morph_name(\Modules\Content\Models\Content::class);
            $data['rel_id'] = $data['parent_page'];
        }
        if (isset($data['content_id'])) {
            $data['rel_type'] = morph_name(\Modules\Content\Models\Content::class);
            $data['rel_id'] = $data['content_id'];
        }

        if (isset($data['id']) and intval($data['id']) != 0 and isset($data['parent_id']) and intval($data['parent_id']) != 0) {
            if ($data['id'] == $data['parent_id']) {
                unset($data['parent_id']);
            }
        }

        return parent::save($data);
    }

}
