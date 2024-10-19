<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModuleLiveEditHandleAction
{

    public array $liveEditActions = [];

    public function getliveEditActions()
    {
        return $this->liveEditActions;
    }

    public function setliveEditActions(array $liveEditActions = [])
    {
        $this->liveEditActions = $liveEditActions;
    }


}
