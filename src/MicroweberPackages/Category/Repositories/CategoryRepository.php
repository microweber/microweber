<?php

namespace MicroweberPackages\Category\Repositories;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Product\Models\Product;
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


    public function getCategoryItemsCountAll()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $categoryItemsCountGroupedByRelType = [];

            $categoryItemsCountData = CategoryItem::rightJoin('content', 'content.id', '=', 'rel_id')
                ->select(['categories_items.parent_id', 'categories_items.rel_type', DB::raw('count( DISTINCT `content`.`id` ) as count')])
                ->where('categories_items.rel_type','content')
                ->groupBy('categories_items.parent_id')
                ->get();

            if ($categoryItemsCountData) {
                foreach ($categoryItemsCountData as $key => $value) {
                    $categoryItemsCountGroupedByRelType[$value->rel_type][$value->parent_id] = $value->count;
                }
            }
            return $categoryItemsCountGroupedByRelType;
        });

    }

    public function getCategoryContentItemsCount($categoryId)
    {
        $categoryItemsCountGroupedByRelType = $this->getCategoryItemsCountAll();

        if (isset($categoryItemsCountGroupedByRelType['content']) and isset($categoryItemsCountGroupedByRelType['content'][$categoryId])) {
            return $categoryItemsCountGroupedByRelType['content'][$categoryId];
        }

        return 0;
    }


    public function countProductsInStock($categoryId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($categoryId) {
            $categoryModelHasAviableProductsCount = 0;
            $categoryModelHasAviableProducts = Category::where('categories.id', $categoryId)
                ->select('categories.id')
                ->where('categories.id', $categoryId)
                ->filter(['hasProductsInStock' => true])
                ->get();
            if ($categoryModelHasAviableProducts) {
                foreach ($categoryModelHasAviableProducts as $categoryModelHasAvailableProduct) {

                    $contentItems = $categoryModelHasAvailableProduct->items()->get();
                    $contentIds = [];
                    foreach ($contentItems as $contentItem) {
                        $contentIds[] = $contentItem->rel_id;
                    }

                    // $categoryModelHasAviableProductsCount = count($contentIds);
                    if (!empty($contentIds)) {
                        $itemsCount = Product::query()->filter(['inStock' => true])
                            ->whereIn('content.id', $contentIds)
                            ->where('content.is_deleted', '=', '0')
                            ->where('content.is_active', '=', '1')
                            ->count();
                        $categoryModelHasAviableProductsCount += $itemsCount;
                    }
                }
            }

            return $categoryModelHasAviableProductsCount;


        });
    }


}
