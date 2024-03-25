<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;

class AdminWebAppManifestTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        if (!route_exists('admin.web-app-manifest')) {
            return '';
        }
        $manifestRoute = route('admin.web-app-manifest');
        $template_headers_src_items[] = '<link crossorigin="use-credentials" rel="manifest" href="' . $manifestRoute . '" />';
        $template_headers_src_items[] = '<meta name="mobile-web-app-capable" content="yes">';

        $template_headers_src = implode("\n", $template_headers_src_items);

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
            'type' => 'admin_web_app_manifest',
        ];
    }


}
