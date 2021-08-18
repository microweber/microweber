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

    public function getById($id) {

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            if (!$id) {
                return;
            }

            if (intval($id) == 0) {
                return false;
            }

            if (is_numeric($id)) {
                $id = intval($id);
            } else {
                $id = mb_trim($id);
            }

            $getCategory = \DB::table('categories')->where('id', $id)->get();
            $getCategory = collect($getCategory)->map(function ($item) {
                return (array)$item;
            })->toArray();

            return $getCategory;
        });
    }


    /**
     * Find content by id.
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

}
