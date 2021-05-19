<?php


namespace MicroweberPackages\Blog;


class FrontendFilter
{
    protected $pagination;
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function pagination()
    {
        return $this->pagination;
    }

    public function total()
    {
        return $this->pagination->total();
    }

    public function count()
    {
        return $this->pagination->count();
    }

    public function items()
    {
        return $this->pagination->items();
    }

    public function sort()
    {

    }

    public function limit()
    {

    }

    public function apply()
    {
        $this->pagination = $this->model->paginate(1);

        return $this;
    }

}
