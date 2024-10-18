<?php

namespace MicroweberPackages\Microweber\Traits;



/**
 * @deprecated
 */
trait ManagesModules
{
    public array $modules = [];

    public function module($type, $moduleClass): void
    {
        $this->modules[$type] = $moduleClass;
    }

    public function getModules(): array
    {
        return $this->modules;
    }

}
