<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class SystemDefaultAdminCssHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $return = '';

//        $enable_default_css = '';
//        $default_css_url = app()->template->get_default_system_ui_css_url();
//        $default_css = '<link rel="stylesheet" href="' . $default_css_url . '" id="mw-system-default-css"  type="text/css" />';
//        $template_config = app()->template->get_config();
//        $enable_default_css = true;
//        if ($template_config and isset($template_config["standalone_ui"]) and $template_config["standalone_ui"]) {
//            $default_css = '';
//
//        }

        $main_css_url = app()->template->get_admin_system_ui_css_url();
        $main_css = '<link rel="stylesheet" href="' . $main_css_url . '" id="mw-admin-main-css"  type="text/css" />';

        $tabler_url = mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/tabler-ui/dist/js/tabler.min.js';
        $main_css .= '<script src="' . $tabler_url . '"></script>';


     //   $return .= $default_css;
        $return .= $main_css;

        return $return;
    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_HEAD;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'system_default_admin_css_head_tags',
        ];
    }
}
