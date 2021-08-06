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
            $get = Category::where('url', $url)->first();
            if ($get != null) { if ($get != null) {
                return $get->toArray();
            }
                return [];
                return $get->toArray();
            }
            return [];
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

            $get = array();
            $get['id'] = $id;
            $get['single'] = true;
            $get['limit'] = 1;

            /*if (isset($q['category_subtype_settings']) and !is_array($q['category_subtype_settings'])) {
                $q['category_subtype_settings'] = @json_decode($q['category_subtype_settings'], true);
            }*/

            return MicroweberQuery::execute(Category::query(), $get);
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
