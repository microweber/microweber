<?php

namespace MicroweberPackages\Microweber\Traits;


/**
 * Trait HasMicroweberModuleOptions
 *
 * Provides functionality to manage module options.
 */
trait HasMicroweberModuleOptions
{


    /**
     * Retrieve the current module options.
     *
     * @return array The current options for the module.
     */
    public function getOptions(): array
    {
        $options = get_module_options($this->params['id'], static::$module);
        if (empty($options)) {
            $options = [];
        }

        return $options;

    }

    public function getOption($key)
    {
        {
            $options = $this->getOptions();
            if ($options) {
                foreach ($options as $option) {
                    if (isset($option['option_key']) and $option['option_key'] == $key) {
                        return $option['option_value'];
                    }
                }
            }
        }
    }

}
