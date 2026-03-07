<?php

namespace Coolsam\Modules\Traits;

trait CanAccessTrait
{
    public static function getCurrentModuleName(): string
    {
        $provider = static::class;
        $provider = explode('\\', $provider);
        $provider = strtolower($provider[1]);

        return $provider;
    }

    public static function canAccess(): bool
    {
        $isModuleEnabled = \Nwidart\Modules\Facades\Module::find(
            static::getCurrentModuleName()
        )?->isEnabled();
        $parentAccess = function_exists('canAccess') ? parent::canAccess() : true;

        if ($isModuleEnabled && $parentAccess) {
            return true;
        }

        return false;
    }
}
