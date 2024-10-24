<?php

namespace MicroweberPackages\Microweber\Traits;



/**
 * Trait HasMicroweberModuleParams
 *
 * Provides functionality to manage module parameters.
 */
trait HasMicroweberModuleParams
{

    public array $params = [];

    /**
     * Retrieve the current module parameters.
     *
     * @return array The current parameters for the module.
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the module parameters.
     *
     * @param array $params The parameters to set for the module.
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;
    }


}
