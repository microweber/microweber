<?php

namespace MicroweberPackages\Core\Events;

class AbstractModelEvent
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
