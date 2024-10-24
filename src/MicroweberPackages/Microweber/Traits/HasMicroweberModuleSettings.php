<?php

namespace MicroweberPackages\Microweber\Traits;


/**
 * Trait HasMicroweberModuleSettings
 *
 * Provides functionality to manage module settings.
 */
trait HasMicroweberModuleSettings
{

    public array $settings = [];

    /**
     * Retrieve the current module settings.
     *
     * @return array The current settings for the module.
     */
    public function getsettings()
    {
        return $this->settings;
    }

    /**
     * Set the module settings.
     *
     * @param array $settings The settings to set for the module.
     */
    public function setsettings(array $settings = [])
    {
        $this->settings = $settings;
    }


}
