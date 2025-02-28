<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class SystemDefaultAdminCssHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $return = '';

        // todo

 return  $return;
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
            'type' => 'system_default_admin_css_head_tags',
        ];
    }
}
