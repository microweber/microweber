<?php

namespace MicroweberPackages\Core\Events;


abstract class AbstractResourceWasDeleted
{
    /**
     * @var Model
     */
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
}
