<?php

namespace MicroweberPackages\Content\Repositories;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Content\Events\ContentWasDestroyed;
use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Content\Events\ContentIsCreating;
use MicroweberPackages\Content\Events\ContentIsUpdating;
use MicroweberPackages\Content\Events\ContentWasCreated;
use MicroweberPackages\Content\Events\ContentWasDeleted;
use MicroweberPackages\Content\Events\ContentWasUpdated;

class ContentRepositoryApi extends BaseRepository
{

    public function __construct(Content $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        event($event = new ContentIsCreating($data));

        $product = $this->model->create($data);

        event(new ContentWasCreated($product, $data));

        return $product;
    }

    public function update($data, $id)
    {
        $product = $this->model->find($id);

        event($event = new ContentIsUpdating($product, $data));

        $product->update($data);

        event(new ContentWasUpdated($product, $data));

        return $product;
    }


    public function delete($id)
    {
        $content = $this->model->find($id);

        event(new ContentWasDeleted($content));

        return $content->delete();
    }


    public function destroy($ids)
    {
        event(new ContentWasDestroyed($ids));

        return $this->model->destroy($ids);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

}
