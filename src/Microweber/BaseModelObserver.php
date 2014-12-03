<?php

class BaseModelObserver
{

    protected function clearCache($model)
    {

        $model_name = $model->table;

        Cache::tags($model_name)->flush();


//        $ql = DB::getQueryLog();
//        $ql = end($ql);
//        $key = crc32('cache' . $ql['query'] . implode($ql['bindings']));
//        Cache::forget($key);
//
//        var_dump('cache cleared', $key, $ql['query']);
//        var_dump(__FILE__ . __LINE__);
    }

    public function saved($model)
    {
        $this->clearCache($model);
    }

    public function saving($model)
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
}