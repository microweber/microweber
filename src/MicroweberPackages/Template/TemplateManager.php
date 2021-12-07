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
    /**
     * An instance of the Microweber Application class.
     *
     * @var
     */
    public $app;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = app();
            }
        }
    }






    public $isBooted = false;

    public function boot_template()
    {
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

            $loadProviders = [];
            if (is_array($module['settings']['service_provider'])) {
                foreach ($module['settings']['service_provider'] as $serviceProvider) {
                    $loadProviders[] = $serviceProvider;
                }
            } else {
                $loadProviders[] = $module['settings']['service_provider'];
            }
            foreach ($loadProviders as $loadProvider) {
                if (class_exists($loadProvider)) {
                    app()->register($loadProvider);
                }
            }
        }
    }
}
