<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class LivewireHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

        /*
                $alpineUrl = mw_includes_url() . 'api/libs/alpine/alpine.min.js';

                $alpineScript = '<script src="' . $alpineUrl . '" defer></script>';*/

        $scripts = app()->make(\Livewire\Mechanisms\FrontendAssets\FrontendAssets::class)->scripts();


        //  $scripts = \Livewire\Livewire::scripts();
        //   $styles = \Livewire\Livewire::styles();
        $styles = app()->make(\Livewire\Mechanisms\FrontendAssets\FrontendAssets::class)->styles();
        // $modal = \Livewire\Livewire::mount('livewire-ui-modal')->html();

        $append_html = '' . "\r\n";
        // $append_html = '<script id="mw-async-alpine" defer src="https://cdn.jsdelivr.net/npm/async-alpine@2.x.x/dist/async-alpine.script.js"></script>' . "\r\n";
        $async_alpine_url = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/async-alpine/async-alpine.script.js';


        $append_html = '<script id="mw-async-alpine" defer src="' . $async_alpine_url . '"></script>' . "\r\n";
        //  $append_html .= $alpineScript . "\r\n";
        $append_html .= $scripts . "\r\n";
        $append_html .= $styles . "\r\n";


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
