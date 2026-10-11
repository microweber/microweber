<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;

class DisableGoogleTranslateHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

        $template_headers_src = '';

        $template_headers_src .= '<meta name="google" content="notranslate" />';
        $template_headers_src .= '<meta name="robots" content="notranslate" />';

        return $template_headers_src;

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
            'type' => 'disable_google_translate',
        ];
    }



}
