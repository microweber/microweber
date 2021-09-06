<?php

namespace MicroweberPackages\Category\Repositories;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Repository\MicroweberQuery;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

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
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {

            $categoryModelHasAviableProducts = Category::where('id', $categoryId)->select('id')->limit(1)->filter(['hasProductsInStock' => true])->first();
            if ($categoryModelHasAviableProducts) {
                return true;
            }

            return false;
        });
    }

}
