<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 1/15/2021
 * Time: 4:47 PM
 */

namespace MicroweberPackages\Option;

class GlobalOptions
{
    protected $options;

    /**
     * GlobalOptions constructor.
     * @param $options
     */
    public function __construct($options)
    {
        foreach ($options as $option) {

            $group = 'all';
            if (!empty($option->option_group)) {
                $group = $option->option_group;
            }

            $this->options[$group][$option->option_key] = $option->option_value;
        }
    }

    /**
     * @param string $option_key
     * @param string $option_group
     * @return bool
     */
    public function get(string $option_key, string $option_group){

        $group = 'all';
        if (!empty($option_group)) {
            $group = $option_group;
        }

        if (isset($this->options[$group][$option_key])) {
            return $this->options[$group][$option_key];
        }

        return false;
    }

}