<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class CustomFontsCssHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $liv_ed_css = '';
        $is_editmode = in_live_edit();
        $liv_ed_css_get_custom_css_content_fonts = app()->template_manager->get_custom_fonts_css_content();
        if ($liv_ed_css_get_custom_css_content_fonts == false) {
            if ($is_editmode) {
                $liv_ed_css = '<link rel="stylesheet"  crossorigin="anonymous" referrerpolicy="no-referrer"  id="mw-custom-user-fonts" type="text/css" />';
            }
        } else {
            $liv_ed_css = app()->template_manager->get_custom_fonts_css_url();

            $liv_ed_css = '<link rel="stylesheet" href="' . $liv_ed_css . '" id="mw-custom-user-fonts" type="text/css"  crossorigin="anonymous" referrerpolicy="no-referrer" />';
        }

        return $liv_ed_css;
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
            'type' => 'custom_fonts_css_head_tags',
        ];
    }
}
