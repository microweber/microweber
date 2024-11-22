<?php

namespace Modules\Post\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use Modules\Post\Models\Post;


class PostApiRepository extends BaseRepository
{

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {

        $post = $this->model->create($data);


        return $post;
    }

    public function update($data, $id)
    {
        $post = $this->model->find($id);


        $post->update($data);


        return $post;
    }


}
