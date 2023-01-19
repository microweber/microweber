<?php

namespace MicroweberPackages\Admin;

use Illuminate\Support\Facades\Event;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\Template\Traits\HasScriptsAndStylesTrait;

class AdminManager
{
    use HasScriptsAndStylesTrait;

    public function __construct()
    {
        $this->addDefaultScripts();
        $this->addDefaultStyles();
        $this->addDefaultCustomTags();
    }

    public function headTags()
    {
        $tags = [];

        $tags[] = $this->styles();
        $tags[] = $this->scripts();
        $tags[] = $this->customHeadTags();

        return implode("\r\n", $tags);
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


        $favicon_image = get_option('favicon_image', 'website');

        if (!$favicon_image) {
            $ui_favicon = mw()->ui->brand_favicon();
            if ($ui_favicon and trim($ui_favicon) != '') {
                $favicon_image = trim($ui_favicon);
            }
        }

        if ($favicon_image) {
            $this->addStyle('favicon', $favicon_image, ['rel' => 'shortcut icon']);
        }

    }



    public function addDefaultCustomTags(): void
    {
        $template_headers_src = mw()->template->admin_head(true);
        if ($template_headers_src != false and $template_headers_src != '') {
            $this->addCustomHeadTag($template_headers_src);
        }
    }

    public function serving(\Closure $callback): void
    {
        Event::listen(ServingAdmin::class, $callback);
    }

}
