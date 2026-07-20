<?php

if (!function_exists('have_license')) {
    /**
     * Check whether a valid license exists for the given module.
     *
     * @param  string|null  $moduleName
     * @return bool
     */
    function have_license(?string $moduleName = null): bool
    {
        if (!app()->bound('system_licenses_manager')) {
            return false;
        }

        return app()->system_licenses_manager->hasLicense($moduleName);
    }
}