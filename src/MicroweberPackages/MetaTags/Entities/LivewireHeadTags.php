<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class LivewireHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        // @todo this is not working correctly
        // it appends in header and adds div , must be in after body
        // see  $this->app->template->append_livewire_to_layout($l);
        return '';

        $alpineUrl = mw_includes_url() . 'api/libs/alpine/alpine.min.js';

        $alpineScript = '<script src="' . $alpineUrl . '" defer></script>';

        $scripts = \Livewire\Livewire::scripts();
        $styles = \Livewire\Livewire::styles();
        $modal = \Livewire\Livewire::mount('livewire-ui-modal')->html();

        $append_html = '' . "\r\n";
        $append_html .= $alpineScript . "\r\n";
        $append_html .= $scripts . "\r\n";
        $append_html .= $styles . "\r\n";

        // @todo this must be in footer
        $append_html .= $modal . "\r\n";


        return $append_html;
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
            'type' => 'live_wire_head_tags',
        ];
    }
}
