<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModuleSettings
{

    public array $settings = [];

    public function getsettings()
    {
        return $this->settings;
    }

    public function setsettings(array $settings = [])
    {
        $this->settings = $settings;
    }


}
