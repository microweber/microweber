<?php

namespace Modules\Content\Repositories;


use MicroweberPackages\Core\Repositories\BaseRepository;
use Modules\Content\Models\Content;

class ContentRepositoryApi extends BaseRepository
{

    public function __construct(Content $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {

        $product = $this->model->create($data);


        return $product;
    }

    public function update($data, $id)
    {
        $product = $this->model->find($id);


        $product->update($data);


        return $product;
    }


    public function delete($id)
    {
        $content = $this->model->find($id);


        return $content->delete();
    }


    public function destroy($ids)
    {

        return $this->model->destroy($ids);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

}
