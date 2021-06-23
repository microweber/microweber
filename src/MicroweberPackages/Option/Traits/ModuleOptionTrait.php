<?php

namespace MicroweberPackages\Option\Traits;

use MicroweberPackages\Option\Models\ModuleOption;

trait ModuleOptionTrait {

    public $memoryModuleOptionGroup = [];

    public function getModuleOptions($optionGroup)
    {
        if (isset($this->memoryModuleOptionGroup[$optionGroup])) {
            return $this->memoryModuleOptionGroup[$optionGroup];
        }

        if ($optionGroup) {

            $this->memoryModuleOptionGroup[$optionGroup] = [];

            $optionGroupShared = $optionGroup;

            // This will be explode the first symbols of the modules option group
            $optionGroupPrefix = explode('--', $optionGroup);
            if (isset($optionGroupPrefix[0]) && !empty($optionGroupPrefix[0])) {
                $optionGroupShared = $optionGroupPrefix[0];
            }

            $allOptions = ModuleOption::where('option_group','LIKE', '%' . $optionGroupShared. '%')->get()->toArray();
            foreach ($allOptions as $option) {
                $this->memoryModuleOptionGroup[$option['option_group']][] = $option;
            }

            return $this->memoryModuleOptionGroup[$optionGroup];
        }

        return false;
    }

    public function getModuleOption($optionKey, $optionGroup, $returnFull)
    {
        if (isset($this->memoryModuleOptionGroup[$optionGroup])) {
            return $this->getOptionFromOptionsArray($optionKey, $this->memoryModuleOptionGroup[$optionGroup], $returnFull);
        }

        if ($optionGroup) {
            $allOptions = $this->getModuleOptions($optionGroup);
            return $this->getOptionFromOptionsArray($optionKey, $allOptions, $returnFull);
        }

        return false;
    }
}
