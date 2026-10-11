<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;
use MicroweberPackages\Template\Facades\TemplateMetaTags;

class TemplateFooterTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {


        $template_headers_src = TemplateMetaTags::footerTags();




        return $template_headers_src;

    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_FOOTER;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'template_footer_tags',
        ];
    }



}
