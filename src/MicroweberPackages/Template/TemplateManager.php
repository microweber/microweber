<?php


/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Template;


class TemplateManager
{

    public $isBooted = false;

    public function boot_template()
    {
        if (!mw_is_installed()) {
            return;
        }
        if ($this->isBooted) {
            return;
        }
        $this->isBooted = true;

        $load_template_functions = TEMPLATE_DIR . 'functions.php';

        if (is_file($load_template_functions)) {
            include_once $load_template_functions;
        }

        $module = app()->template->get_config();

        if (isset($module['settings']) and $module['settings'] and isset($module['settings']['service_provider']) and $module['settings']['service_provider']) {

            app()->module_manager->boot_module($module);
        }
    }
}
