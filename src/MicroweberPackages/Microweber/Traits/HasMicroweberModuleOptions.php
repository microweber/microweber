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
     * @internal
     */
    public function getOptionsFull(): array
    {
        $options = get_module_options($this->params['id'], static::$module);
        if (empty($options)) {
            $options = [];
        }
        return $options;
    }

    /**
     * Retrieve the current module options.
     *
     * @return array The current options for the module.
     */
    public function getOptions(): array
    {
        $savedOptions = get_module_options($this->params['id'], static::$module);
        $options = [];
        if ($savedOptions) {
            foreach ($savedOptions as $option) {
                if (isset($option['option_key'])) {
                    $options[$option['option_key']] = $option['option_value'] ?? null;
                }
            }
        }


        return $options;

    }

    public function saveOption($key,$value = null )
    {

        $save = save_module_option($key, $value,$this->params['id']  , static::$module);

    }
    public function getOption($key, $default = null)
    {

        $options = $this->getOptions();
        if ($options) {
            foreach ($options as $optionKey => $optionValue) {
                if ($optionKey == $key and $optionValue) {
                    return $optionValue;
                }
            }
        }
        return $default;
    }

    public static function getTranslatableOptionKeys(): array
    {
        if (isset(static::$translatableOptions) and is_array(static::$translatableOptions)) {
            return static::$translatableOptions;
        }

        return [];
    }
}
