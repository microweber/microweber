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

            $allOptions = ModuleOption::where('option_group', $optionGroup)->get()->toArray();
              $this->memoryModuleOptionGroup[$optionGroup] = $allOptions;
            return $allOptions;
        }

        return false;
    }

    public function getModuleOption($optionKey, $optionGroup, $returnFull)
    {
        if (isset($this->memoryModuleOptionGroup[$optionGroup])) {
            return $this->getOptionFromOptionsArray($optionKey, $this->memoryModuleOptionGroup[$optionGroup], $returnFull);
        }

        if ($optionGroup) {

            $allOptions = ModuleOption::where('option_group', $optionGroup)->get()->toArray();

            $this->memoryModuleOptionGroup[$optionGroup] = $allOptions;
            return $this->getOptionFromOptionsArray($optionKey, $allOptions, $returnFull);
        }

        return false;
    }
}
