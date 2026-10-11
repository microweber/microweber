<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;

class AdminHeadTagsFromAdminManager implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $template_headers_src =  \MicroweberPackages\Admin\Facades\AdminManager::headTags();


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
            'type' => 'admin_head_tags_from_admin_manager',
        ];
    }
}
