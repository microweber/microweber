<?php

namespace MicroweberPackages\Microweber\Traits;



/**
 * Trait HasMicroweberModuleOptions
 *
 * Provides functionality to manage module options.
 */
trait HasMicroweberModuleOptions
{

    public array $options = [];


    /**
     * Retrieve the current module options.
     *
     * @return array The current options for the module.
     */
    public function getOptions()
    {

 // get_module_options($this->params['id'], $this->options);
        return $this->options;
    }

    /**
     * Set the module options.
     *
     * @param array $options The options to set for the module.
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }


}
