<?php

namespace MicroweberPackages\Core\Events;

abstract class AbstractResourceWasCreated
{
    /**
     * @var Model
     */
    private $model;

    public function __construct($model, array $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
