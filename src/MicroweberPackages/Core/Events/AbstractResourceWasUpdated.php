<?php

namespace MicroweberPackages\Core\Events;

abstract class AbstractResourceWasUpdated
{
    /**
     * @var Model
     */
    private $model;
    private $data;

    public function __construct($model)
    {
        $this->model = $model;

    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }
}
