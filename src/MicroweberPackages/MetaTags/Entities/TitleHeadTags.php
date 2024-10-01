<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;

class TitleHeadTags implements TagInterface, \Stringable
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


        if (isset($content['content_meta_title']) and $content['content_meta_title'] != '') {
            $template_headers_src = $template_headers_src . "\n" . '<title>' . trim($content['title']) . '</title>' . "\n";
        } else if (isset($content['title']) and $content['title'] != '') {
            $template_headers_src = $template_headers_src . "\n" . '<title>' . trim($content['title']) . '</title>' . "\n";
        }
        if (isset($content['description']) and $content['description'] != '') {
            $template_headers_src = $template_headers_src . "\n" . '<meta name="description" content="' . htmlentities($content['description']) . '">' . "\n";
        }
        if (isset($content['content_meta_keywords']) and $content['content_meta_keywords'] != '') {
            $template_headers_src = $template_headers_src . "\n" . '<meta name="keywords" content="' . htmlentities($content['content_meta_keywords']) . '">' . "\n";
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
            'type' => 'title_head_tags',
        ];
    }


}
