<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Vite;

class AdminFilamentJsLibsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $libs = Vite::asset('src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-libs.js');
        $lib_tynymce = mw_includes_url() . 'api/libs/tinymce/tinymce.min.js';


        $append_html = '' . "\r\n";
        $append_html .= '<script src="' . $lib_tynymce . '" id="mw-tynymce-js-libs-scripts"></script>' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $libs . '" type="module" async id="mw-filament-js-libs-scripts"></script>' . "\r\n";

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
            'type' => 'admin-filament-js-libs',
        ];
    }
}
