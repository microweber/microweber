<?php

namespace MicroweberPackages\Microweber\Traits;


trait HasMicroweberModuleParams
{

    public array $params = [];

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params = [])
    {
        $this->params = $params;
    }


}
