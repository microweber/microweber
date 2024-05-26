<?php

namespace MicroweberPackages\Livewire\Mechanisms\FrontendAssets;

use Livewire\Mechanisms\FrontendAssets\FrontendAssets;
use MicroweberPackages\Livewire\LivewireFronterdAssetsManager;

class MwLivewireFrontendAssets extends FrontendAssets
{

    protected static function usePublishedAssetsIfAvailable($url, $manifest, $nonce)
    {
        $assetWarning = null;

        $helper = new LivewireFronterdAssetsManager();
        $helper->copyAssets();

        $url = $helper->getAssetPath();

   //     $url = app('livewire')->getAssetPath();



        return [$url, $assetWarning];
    }

    public static function scrip111ts($options = [])
    {
        dd($options);

        app(static::class)->hasRenderedScripts = true;

        $debug = config('app.debug');

        $scripts = static::js($options);

        // HTML Label.
        $html = $debug ? ['<!-- Livewire Scripts -->'] : [];

        $html[] = $scripts;

        return implode("\n", $html);
    }
}
