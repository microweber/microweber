<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Vite;

class MwSettingsJsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $get_apijs_settings_url = mw()->template->get_apijs_settings_url();
        $append_html = '' . "\r\n";
        $append_html .= '<script src="' . $get_apijs_settings_url . '" id="mw-api-settings"></script>' . "\r\n";

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
            'type' => 'apijs-settings',
        ];
    }
}
