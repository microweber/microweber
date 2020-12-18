<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/5/2020
 * Time: 4:27 PM
 */

namespace MicroweberPackages\Core\Repositories;

interface BaseRepositoryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);


    /**
     * Destroy the models for the given IDs.
     *
     * @param  \Illuminate\Support\Collection|array|int|string  $ids
     * @return int
     */
    public function destroy($ids);

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete($id);
}