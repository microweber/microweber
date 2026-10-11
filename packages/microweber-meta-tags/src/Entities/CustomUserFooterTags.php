<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class CustomUserFooterTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $websiteOptions = app()->option_repository->getWebsiteOptions();
        if (isset($websiteOptions['website_footer']) && !empty($websiteOptions['website_footer'])) {
            $append_html = $websiteOptions['website_footer'];
        } else {
            $append_html = '';
        }


        return $append_html;
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
            'type' => 'custom_user_footer_tags',
        ];
    }
}
