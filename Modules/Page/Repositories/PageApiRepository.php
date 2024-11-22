<?php

namespace Modules\Page\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;

use Modules\Page\Models\Page;

class PageApiRepository extends BaseRepository
{
    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {

        $page = $this->model->create($data);


        return $page;
    }

    public function update($data, $id)
    {
        $page = $this->model->find($id);


        $page->update($data);


        return $page;
    }

    public function delete($id)
    {
        $page = $this->model->find($id);


        return $page->delete();
    }


    public function destroy($ids)
    {

        return $this->model->destroy($ids);
    }
}
