<?php

namespace MicroweberPackages\Core\Events;

abstract class AbstractResourceWasUpdated
{
    /**
     * @var Model
     */
    private $model;

    public function __construct($request, $model)
    {
        $this->request = $request;
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
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
