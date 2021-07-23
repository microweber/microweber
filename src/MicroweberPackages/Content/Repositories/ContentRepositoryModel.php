<?php


namespace MicroweberPackages\Content\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class ContentRepositoryModel extends AbstractRepository
{

    protected $eventFlushCache = true;
    protected $cacheMinutes = 6000;


    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Content::class;




    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function findById($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $this->newQuery();

            return $this->query
                ->where('id', $id)
                ->limit(1)
                ->first();
        });
    }

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

            return $this->findById($id)->media;


        });
    }


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

            return $this->findById($id)->contentData;


        });
    }


    /**
     * Filter by author attribute
     *
     * @return self
     */
    public function scopeIsShop()
    {
        return $this->addScopeQuery(function ($query) {
            return $query->where('is_shop', '=', 1);
        });
    }

}