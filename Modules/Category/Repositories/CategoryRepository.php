<?php

namespace Modules\Category\Repositories;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryItem;

class CategoryRepository extends AbstractRepository
{

    /**
     * Specify Models class name
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
            return Category::tree();
        });
    }

    public function getChildsTree($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {
            return Category::getChildsTree($categoryId);
        });
    }

    /**
     * @param mixed $categoryId
     * @return boolean|array
     */
    public function getSubCategories($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {
            return Category::getSubCategories($categoryId);
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
        return Category::hasProductsInStock($categoryId);
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
            return Category::getItemsInStockCountAll();
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
        return Category::getProductsInStockCount($categoryId);
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
