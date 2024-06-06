<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Vite;

class AdminFilamentJsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $apijs_combined_loaded = Vite::asset('src/MicroweberPackages/LiveEdit/resources/js/ui/admin-filament-app.js');
        $append_html = '' . "\r\n";
        $append_html .= '<script src="' . $apijs_combined_loaded . '" type="module" id="mw-filament-js-core-scripts"></script>' . "\r\n";

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
