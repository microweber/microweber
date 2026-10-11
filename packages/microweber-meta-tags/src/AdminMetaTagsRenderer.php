<?php

namespace MicroweberPackages\MetaTags;

use Butschster\Head\Facades\Meta;

class AdminMetaTagsRenderer
{
    public function getHeadMetaTags()
    {
        Meta::includePackages([
            'admin'
        ]);
        $meta = Meta::placement('head')->toHtml();
        return $meta;
    }

    public function getFooterMetaTags()
    {
        Meta::includePackages([
            'admin'
        ]);
        $meta = Meta::placement('footer')->toHtml();
        return $meta;
    }
}
