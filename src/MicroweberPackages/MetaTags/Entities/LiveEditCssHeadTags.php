<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class LiveEditCssHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $live_edit_url = app()->template->liveEditCssAdapter->getLiveEditCssUrl();
        $liv_ed_css = '';
        if ($live_edit_url) {
            $liv_ed_css = '<link rel="stylesheet" href="' . $live_edit_url . '" id="mw-template-settings" crossorigin="anonymous" referrerpolicy="no-referrer" type="text/css" />';
        }
        return $liv_ed_css;
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
            'type' => 'live_edit_css_head_tags',
        ];
    }
}
