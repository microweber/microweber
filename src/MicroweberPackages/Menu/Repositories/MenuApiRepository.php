<?php

namespace MicroweberPackages\Menu\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Menu\Events\MenuIsCreating;
use MicroweberPackages\Menu\Events\MenuIsUpdating;
use MicroweberPackages\Menu\Events\MenuWasCreated;
use MicroweberPackages\Menu\Events\MenuWasDeleted;
use MicroweberPackages\Menu\Events\MenuWasUpdated;
use MicroweberPackages\Menu\Models\Menu;
use MicroweberPackages\Menu\Models\Page;

class MenuApiRepository extends BaseRepository
{
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        event($event = new MenuIsCreating($data));

        $page = $this->model->create($data);

        event(new MenuWasCreated($page, $data));

        return $page;
    }

    public function update($data, $id)
    {
        $page = $this->model->find($id);

        event($event = new MenuIsUpdating($page, $data));

        $page->update($data);

        event(new MenuWasUpdated($page, $data));

        return $page;
    }

    public function delete($id)
    {
        $page = $this->model->find($id);

        event(new MenuWasDeleted($page));

        return $page->delete();
    }


    public function destroy($ids)
    {
        event(new MenuWasDestroy($ids));

        return $this->model->destroy($ids);
    }
}
