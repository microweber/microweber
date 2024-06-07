<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class IconFontsHeadTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $html = '';
        $get_icon_fonts_stylesheet_url = app()->template->get_icon_fonts_stylesheet_url();

        $preload = true;
        if ($this->getPlacement() == Meta::PLACEMENT_FOOTER) {
            $preload = false;
        }
        if ($get_icon_fonts_stylesheet_url) {

            if ($preload) {
                $html = '<link rel="preload" href="' . $get_icon_fonts_stylesheet_url . '" as="style" crossorigin="anonymous" referrerpolicy="no-referrer" />';
            } else {
                $html = '<link rel="stylesheet" id="mw-icon-fonts-combined" href="' . $get_icon_fonts_stylesheet_url . '" type="text/css"  crossorigin="anonymous" referrerpolicy="no-referrer" />';
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
