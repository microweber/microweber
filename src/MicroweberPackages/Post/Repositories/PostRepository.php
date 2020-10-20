<?php

namespace MicroweberPackages\Post\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Post\Events\PostIsCreating;
use MicroweberPackages\Post\Events\PostIsUpdating;
use MicroweberPackages\Post\Events\PostWasCreated;
use MicroweberPackages\Post\Events\PostWasDeleted;
use MicroweberPackages\Post\Events\PostWasUpdated;
use MicroweberPackages\Post\Models\Post;

class PostRepository extends BaseRepository
{

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        event($event = new PostIsCreating($data));

        $post = $this->model->create($data);

        event(new PostWasCreated($post, $data));

        return $post;
    }

    public function update($data, $id)
    {
        $post = $this->model->find($id);

        event($event = new PostIsUpdating($post, $data));

        $post->update($data);

        event(new PostWasUpdated($post, $data));

        return $post;
    }



}
