<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class FaviconHeadTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $favicon_image = false;
        $favicon_html = '';

        if (isset($this->websiteOptions['favicon_image'])) {
            $favicon_image = $this->websiteOptions['favicon_image'];
        }

        if (!$favicon_image) {
            $ui_favicon = mw()->ui->brand_favicon();
            if ($ui_favicon and trim($ui_favicon) != '') {
                $favicon_image = trim($ui_favicon);
            }
        }
        if ($favicon_image) {
            $favicon_html = '<link rel="shortcut icon" href="' . $favicon_image . '" />';
        }
        return $favicon_html;
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
            'type' => 'favicon_head_tag',
        ];
    }
}
