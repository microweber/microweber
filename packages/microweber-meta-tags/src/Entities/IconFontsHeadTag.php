<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class IconFontsHeadTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $html = '';
        $get_icon_fonts_stylesheet_url = app()->template_manager->get_icon_fonts_stylesheet_url();
        $sets = app()->template_manager->iconFontsAdapter->getIconSets();


        $families = [];
        if ($sets) {
            foreach ($sets as $set) {
                if (isset($set['font_family'])) {
                    $families[] = $set['font_family'];
                }
            }
        }
        if ($families) {
            asort($families);
        }
        $families_attr = implode(',', $families);


        $preload = true;
        if ($this->getPlacement() == Meta::PLACEMENT_FOOTER) {
            $preload = false;
        }
        if ($get_icon_fonts_stylesheet_url) {

            if ($preload) {
                $html = '<link rel="preload" href="' . $get_icon_fonts_stylesheet_url . '" as="style" crossorigin="anonymous" referrerpolicy="no-referrer" />';
            } else {
                $html = '<link rel="stylesheet" id="mw-icon-fonts-combined" href="' . $get_icon_fonts_stylesheet_url . '" data-icon-families="' . $families_attr . '" type="text/css"  crossorigin="anonymous" referrerpolicy="no-referrer" />';
            }
        }

        return $html;
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
            'type' => 'icon_fonts_head_tag',
        ];
    }
}
