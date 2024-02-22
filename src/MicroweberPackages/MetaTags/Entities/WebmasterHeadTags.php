<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class WebmasterHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

        $contentId = content_id();
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
        $in = new \MicroweberPackages\Template\Adapters\RenderHelpers\TemplateMetaTagsRenderer();
        $headers = $in->get_template_meta_webmaster_tags();
        foreach ($headers as $headers_append) {
            if (is_string($headers_append)) {
                $template_headers_src = $template_headers_src . "\n" . $headers_append;
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
            'type' => 'webmaster_head_tags',
        ];
    }
}
