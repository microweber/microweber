<?php

namespace MicroweberPackages\Core\Events;

abstract class AbstractResourceIsCreating
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function getData()
    {
        return $this->data;
    }

}
