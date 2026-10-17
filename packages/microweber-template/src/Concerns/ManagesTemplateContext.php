<?php

namespace MicroweberPackages\Template\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ManagesTemplateContext
{
    public function defineConstants($content = false)
    {
        return $this->templateAdapter->defineConstants($content);
    }

    public function defineTemplateConstants(): void
    {
        $this->templateAdapter->defineTemplateConstants();
    }

    public function getContentId()
    {
        return $this->templateAdapter->getContentId();
    }

    public function getProductId()
    {
        return $this->templateAdapter->getProductId();
    }

    public function getPageId()
    {
        return $this->templateAdapter->getPageId();
    }

    public function getPostId()
    {
        return $this->templateAdapter->getPostId();
    }

    public function getCategoryId()
    {
        return $this->templateAdapter->getCategoryId();
    }

    public function getMainPageId()
    {
        return $this->templateAdapter->getMainPageId();
    }

    public function boot_template()
    {
        if (!mw_is_installed()) {
            return;
        }
        if ($this->isBooted) {
            return;
        }
        $this->isBooted = true;
        //load_service_providers_for_template();

        // load_functions_files_for_template();
//        $load_template_functions = TEMPLATE_DIR . 'functions.php';
//
//        if (is_file($load_template_functions)) {
//            include_once $load_template_functions;
//        }

// //moved to load_all_service_providers_for_modules function
//        $module = app()->template_manager->get_config();
//
//        if (isset($module['settings']) and $module['settings'] and isset($module['settings']['service_provider']) and $module['settings']['service_provider']) {
//
//            app()->module_manager->boot_module($module);
//        }
    }
}
