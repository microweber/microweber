<?php

namespace MicroweberPackages\Option\Traits;

use MicroweberPackages\Option\Models\ModuleOption;

trait ModuleOptionTrait {

    public $memoryModuleAllOptions = [];
    public $memoryModuleOptionGroup = [];

    public function getModuleOptions($optionGroup)
    {
        if (isset($this->memoryModuleOptionGroup[$optionGroup])) {
            return $this->memoryModuleOptionGroup[$optionGroup];
        }

        if ($optionGroup) {
            $this->memoryModuleOptionGroup[$optionGroup] = [];

            // >where('option_group', $optionGroup)

            if (empty($this->memoryModuleAllOptions)) {
                $this->memoryModuleAllOptions = ModuleOption::select(['id','option_key','option_group','option_value'])->get()->toArray();
            }

            foreach ($this->memoryModuleAllOptions as $option) {
                $this->memoryModuleOptionGroup[$option['option_group']][] = $option;
            }

            return $this->memoryModuleOptionGroup[$optionGroup];
        }

        return false;
    }

    public function getModuleOption($optionKey, $optionGroup, $returnFull = false)
    {
        if (isset($this->memoryModuleOptionGroup[$optionGroup])) {
            return $this->getOptionFromOptionsArray($optionKey, $this->memoryModuleOptionGroup[$optionGroup], $returnFull);
        }

        if ($optionGroup) {
            //$allOptions = ModuleOption::select(['id','option_key','option_group','option_value'])->where('option_group', $optionGroup)->get()->toArray();
            //$this->memoryModuleOptionGroup[$optionGroup] = $allOptions;
            $allOptions = $this->getModuleOptions($optionGroup);
            return $this->getOptionFromOptionsArray($optionKey, $allOptions, $returnFull);
        }

        return false;
    }
}
