<?php

namespace MicroweberPackages\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Models\Content;

/**
 * Interface CoreRepository
 * @package Modules\Core\Repositories
 */
class BaseRepository implements BaseRepositoryInterface
{
    /**
     *  Model property on class instances
     *
     * @var Model Eloquent
     */
    protected $model;

    /**
     * Constructor to bind model to repo
     *
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all instances of model
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    public function filter($request) {
        return $this->model->filter($request);
    }

    /**
     * Create a new record in the database
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update record in the database
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    /**
     * Remove record from the database
     *
     * @param $id
     * @return bool|null
     */
    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    /**
     * Remove records from the database
     *
     * @param array|\Illuminate\Support\Collection|int|string $ids
     * @return int
     */
    public function destroy($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Find the record with the given id
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Show the record with the given id and relations
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get the associated model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the associated model
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Eager load database relationships
     *
     * @param $relations
     * @return mixed
     */
    public function with($relations)
    {
        return $this->model->with($relations);
    }
}
