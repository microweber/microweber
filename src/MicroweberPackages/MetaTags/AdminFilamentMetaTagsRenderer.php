<?php

namespace MicroweberPackages\MetaTags;

use Butschster\Head\Facades\Meta;

class AdminFilamentMetaTagsRenderer
{
    public function getHeadMetaTags()
    {
        Meta::includePackages([
            'filament'
        ]);
        $meta = Meta::placement('head')->toHtml();
        return $meta;
    }

    public function getFooterMetaTags()
    {
        Meta::includePackages([
            'filament'
        ]);
        $meta = Meta::placement('footer')->toHtml();
        return $meta;
    }
}
