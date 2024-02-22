<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Str;

class CustomHeadTagsFromSiteHeaderEvent implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $the_active_site_template = app()->template->templateAdapter->getTemplateFolderName();

        $headers = event_trigger('site_header', $the_active_site_template);
        $template_headers_append = '';
        if (is_array($headers)) {
            foreach ($headers as $modify) {
                if ($modify != false and is_string($modify) and $modify != '') {
                    $template_headers_append = $template_headers_append . $modify;
                }
            }

        }
        return $template_headers_append;
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
            'type' => 'custom_head_tags_from_site_header_event',
        ];
    }
}
