<?php


namespace MicroweberPackages\Content\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

/**
 * @mixin AbstractRepository
 */
class ContentRepository extends AbstractRepository
{


    protected $searchable = [
        'id',
        'title',
        'content',
        'content_body',
        'content_type',
        'content_subtype',
        'description',
        'is_home',
        'is_shop',
        'is_deleted',
        'subtype',
        'subtype_value',
        'parent',
        'layout_file',
        'active_site_template',
        'url',
        'content_meta_title',
        'content_meta_keywords',
    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Content::class;


    /**
     * Filter results by given query params.
     *
     * @param string|array $queries
     *
     * @return self
     */
    public function searchByParams($params)
    {

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {

            if (isset($params['count']) and $params['count']) {
                $result = $this->search($params)->count();
            } else if (isset($params['single'])) {
                $result = $this->select(['id'])->search($params)->limit(1)->all();
            } else {
                $result = $this->select(['id'])->search($params)->all(['id']);

            }


            if ($result) {

                $result = $result->toArray();
                if ($result) {
                    $ready = [];
                    foreach ($result as $dataById) {
                        $dataById = $dataById['id'];
                        $find = $this->findById($dataById);
                        if ($find) {
                            $find = $find->toArray();
                        }
                        $ready[$dataById] = $find;
                    }
                    $result = $ready;
                }

                if (isset($params['single'])) {
                    $result = array_pop($result);
                }
            }

            return $result;


        });


    }
//
//
//
//
//
    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getMedia($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            return $this->findById($id)->media->toArray();


        });
    }

//

    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getContentData($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            return $this->findById($id)->contentData->toArray();


        });
    }
//
//
//    /**
//     * Filter by author attribute
//     *
//     * @return self
//     */
//    public function scopeIsShop()
//    {
//        return $this->addScopeQuery(function ($query) {
//            return $query->where('is_shop', '=', 1);
//        });
//    }

}