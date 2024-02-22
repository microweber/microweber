<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;

class AuthorHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

        $contentId = content_id();
        if (!$contentId) {
            return '';
        }
        $content = get_content_by_id($contentId);
        if (!$content) {
            return '';
        }
        $template_headers_src = '';
        if (isset($content['created_by'])) {
            $author = app()->user_manager->get_by_id($content['created_by']);
            if (is_array($author) and isset($author['profile_url']) and $author['profile_url'] != false) {
                $template_headers_src = $template_headers_src . "\n" . '<link rel="author" href="' . trim($author['profile_url']) . '" />' . "\n";
            }
        }


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
            'type' => 'author_head_tags',
        ];
    }



}
