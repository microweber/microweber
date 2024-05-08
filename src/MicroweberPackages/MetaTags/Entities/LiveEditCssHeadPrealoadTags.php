<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class LiveEditCssHeadPrealoadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {


        $live_edit_url = app()->template->liveEditCssAdapter->getLiveEditCssUrl();

        if (($live_edit_url)) {
            $liv_ed_css = '<link  rel="preload" as="style"  href="' . $live_edit_url . '"  crossorigin="anonymous" referrerpolicy="no-referrer" type="text/css" />';
            return $liv_ed_css;

        }
        return '';
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
            'type' => 'live_edit_css_head_tags_preload',
        ];
    }
}
