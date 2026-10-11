<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class IconFontsFooterTag extends IconFontsHeadTag
{
    public function getPlacement(): string
    {
        return Meta::PLACEMENT_FOOTER;
    }
}
