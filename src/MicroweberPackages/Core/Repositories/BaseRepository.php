<?php

namespace MicroweberPackages\Core\Repositories;

/**
 * Interface CoreRepository
 * @package Modules\Core\Repositories
 */
class BaseRepository
{
    /**
     * @param  int $id
     * @return $model
     */
    public function find($id)
    {

    }

    /**
     * Return a collection of all elements of the resource
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return [];
    }


    /**
     * Create a resource
     * @param  $data
     * @return $model
     */
    public function create($data)
    {
        return 'create';
    }


    /**
     * Update a resource
     * @param  $model
     * @param  array $data
     * @return $model
     */
    public function update($model, $data)
    {
        return 'update';
    }

    /**
     * Destroy a resource
     * @param  $model
     * @return bool
     */
    public function destroy($model)
    {
        return 'destroy';
    }
}
