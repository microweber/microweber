<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Str;

class CustomFooterTagsFromCallback implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $template_headers_src = app()->template->foot(true);

        $template_headers_src_callback = app()->template->foot_callback();
        if (is_array($template_headers_src_callback) and !empty($template_headers_src_callback)) {
            foreach ($template_headers_src_callback as $template_headers_src_callback_str) {
                if (is_string($template_headers_src_callback_str)) {
                    $template_headers_src = $template_headers_src . "\n" . $template_headers_src_callback_str;
                }
            }
        }
        return $template_headers_src;
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
            'type' => 'custom_footer_tags_from_callback',
        ];
    }
}
