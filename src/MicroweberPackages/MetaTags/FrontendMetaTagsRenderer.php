<?php

namespace MicroweberPackages\MetaTags;

use Butschster\Head\Facades\Meta;

class FrontendMetaTagsRenderer
{
    public function getHeadMetaTags()
    {
        Meta::includePackages([
            'frontend'
        ]);
        $meta = Meta::placement('head')->toHtml();
        return $meta;
    }

    public function getFooterMetaTags()
    {
        Meta::includePackages([
            'frontend'
        ]);
        $meta = Meta::placement('footer')->toHtml();
        return $meta;
    }
}
