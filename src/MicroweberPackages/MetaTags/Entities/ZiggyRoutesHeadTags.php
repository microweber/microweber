<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;
use MicroweberPackages\Template\Adapters\RenderHelpers\ZiggyInlineJsRouteGenerator;
use MicroweberPackages\Template\Facades\TemplateMetaTags;

class ZiggyRoutesHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {


        $template_headers_src = TemplateMetaTags::headTags();

        $except = ['_debugbar.*', 'ignition.*', 'dusk.*', 'horizon.*', 'l5-swagger.*'];
        if (!is_admin()) {
            $except[] = 'admin.*';
            $except[] = 'filament.admin.*';
            $except[] = 'api.*';

        }
        config()->set('ziggy.except', $except);

        $ziggy = new ZiggyInlineJsRouteGenerator();
        $jsRoutes = $ziggy->generate();
dd($jsRoutes);

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
            'type' => 'ziggy_routes_head_tags',
        ];
    }



}
