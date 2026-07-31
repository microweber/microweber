<?php

declare(strict_types=1);

use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;

if (!function_exists('template_custom_css')) {
    /**
     * Resolve the TemplateCustomCssManager singleton.
     */
    function template_custom_css(): TemplateCustomCssManager
    {
        return app(TemplateCustomCssManager::class);
    }
}

if (!function_exists('template_live_edit_css')) {
    /**
     * Resolve the LiveEditCssManager singleton.
     */
    function template_live_edit_css(): LiveEditCssManager
    {
        return app(LiveEditCssManager::class);
    }
}

if (!function_exists('template_user_custom_css')) {
    /**
     * Resolve the CustomCssManager singleton (user custom CSS option).
     */
    function template_user_custom_css(): CustomCssManager
    {
        return app(CustomCssManager::class);
    }
}
