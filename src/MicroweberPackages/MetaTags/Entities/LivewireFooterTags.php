<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class LivewireFooterTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

        $append_html = '';

        $modal = \Livewire\Livewire::mount('livewire-ui-modal')->html();

         $append_html .= $modal . "\r\n";

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
            'type' => 'live_wire_footer_tags',
        ];
    }
}
