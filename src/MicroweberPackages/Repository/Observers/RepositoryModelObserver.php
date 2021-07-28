<?php

namespace MicroweberPackages\Repository\Observers;


class RepositoryModelObserver
{
    public function saved($model)
    {
        $this->clearCache($model);
    }

    public function saving($model)
    {
        $this->clearCache($model);
    }

    public function updating($model)
    {
        $this->clearCache($model);
    }

    public function updated($model)
    {

        $this->clearCache($model);
    }

    public function deleted($model)
    {
        $this->clearCache($model);
    }

    public function restored($model)
    {
        $this->clearCache($model);
    }

    public function created($model)
    {
        $this->clearCache($model);
    }

    protected function clearCache($model)
    {

        //  clearcache();
        // TODO
        //Cache::tags($model->table)->flush();
    }
}
