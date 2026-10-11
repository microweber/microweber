<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class FrontendAnimationsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $animations_html = '';
        $animations = get_option('animations-global', 'template');
        if ($animations) {
            $animations_html = '<script id="template-animations-data">mw.__pageAnimations = ' . $animations . '</script>';

        }

        return $animations_html;
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
            'type' => 'frontend_animations_script_tag',
        ];
    }
}
