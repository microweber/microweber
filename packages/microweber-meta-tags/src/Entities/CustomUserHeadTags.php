<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class CustomUserHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $websiteOptions = app()->option_repository->getWebsiteOptions();
        if (isset($websiteOptions['website_head']) && !empty($websiteOptions['website_head'])) {
            $append_html = $websiteOptions['website_head'];
        } else {
            $append_html = '';
        }


        return $append_html;
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
            'type' => 'custom_user_head_tags',
        ];
    }
}
