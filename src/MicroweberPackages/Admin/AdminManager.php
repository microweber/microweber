<?php

namespace MicroweberPackages\Admin;

class AdminManager
{
    public $scripts = [];
    public $styles = [];

    public function __construct()
    {
        $this->addDefaultScripts();
        $this->addDefaultStyles();
    }

    public function addDefaultScripts(): void
    {

        $apijs_combined_loaded = app()->template->get_apijs_combined_url();
        $this->addScript('mw-api-js', $apijs_combined_loaded);

    }

    public function addDefaultStyles(): void
    {
        $default_css_url = app()->template->get_default_system_ui_css_url();
        $this->addStyle('mw-default-css', $default_css_url);

        $main_css_url = app()->template->get_admin_system_ui_css_url();
        $this->addStyle('mw-ui-css', $main_css_url);

    }

    public function addScript($id, $src)
    {
        $this->scripts[$id] = $src;

    }

    public function addStyle($id, $src)
    {
        $this->styles[$id] = $src;

    }

    public function styles()
    {
        $ready = [];

        if ($this->styles) {
            foreach ($this->styles as $key => $script) {
                $ready[] = '<link rel="stylesheet" id="'.$key.'" href="'.$script.'" type="text/css">';
            }
        }

        return implode("\r\n", $ready);

    }

    public function scripts()
    {
        $ready = [];
        if ($this->scripts) {
            foreach ($this->scripts as $key => $script) {
                $ready[] = '<script id="' . $key . '" src="' . $script . '"></script>';
            }
        }

        $favicon_image = get_option('favicon_image', 'website');

        if (!$favicon_image) {
            $ui_favicon = mw()->ui->brand_favicon();
            if ($ui_favicon and trim($ui_favicon) != '') {
                $favicon_image = trim($ui_favicon);
            }
        }

        if ($favicon_image) {
            $ready[] = '<link rel="shortcut icon" href="' . $favicon_image . '" />';
        }

        $template_headers_src = mw()->template->admin_head(true);
        if ($template_headers_src != false and $template_headers_src != '') {
            $ready[] = $template_headers_src;
        }


        return implode("\r\n", $ready);
    }
}
