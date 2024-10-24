<?php

namespace MicroweberPackages\Microweber\Traits;


/**
 * Trait HasMicroweberModuleLiveEditHandleAction
 *
 * Provides functionality to manage live edit actions for modules.
 */
trait HasMicroweberModuleLiveEditHandleAction
{

    public array $liveEditActions = [];

    /**
     * Retrieve the current live edit actions.
     *
     * @return array The current live edit actions for the module.
     */
    public function getliveEditActions()
    {
        return $this->liveEditActions;
    }

    /**
     * Set the live edit actions for the module.
     *
     * @param array $liveEditActions The live edit actions to set for the module.
     */
    public function setliveEditActions(array $liveEditActions = [])
    {
        $this->liveEditActions = $liveEditActions;
    }


}
