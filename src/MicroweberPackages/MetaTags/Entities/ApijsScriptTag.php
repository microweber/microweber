<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class ApijsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $apijs_combined_loaded = app()->template->get_apijs_combined_url();
        $append_html = '' . "\r\n";
        $append_html .= '<script src="' . $apijs_combined_loaded . '" id="mw-js-core-scripts"></script>' . "\r\n";

        return $append_html;
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
            'type' => 'apijs',
        ];
    }
}
