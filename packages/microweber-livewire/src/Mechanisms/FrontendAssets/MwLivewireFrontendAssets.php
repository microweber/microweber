<?php

namespace MicroweberPackages\Livewire\Mechanisms\FrontendAssets;

use Livewire\Mechanisms\FrontendAssets\FrontendAssets;
use MicroweberPackages\Livewire\LivewireFrontendAssetsManager;

class MwLivewireFrontendAssets extends FrontendAssets
{

    /**
     * @param string $url
     * @param array<string, string> $manifest
     * @param string $nonce
     * @return array{0: string, 1: null}
     */
    protected static function usePublishedAssetsIfAvailable($url, $manifest, $nonce): array
    {
        $assetWarning = null;

        $helper = new LivewireFrontendAssetsManager();
        $helper->copyAssets();

        $url = $helper->getAssetPath();

   //     $url = app('livewire')->getAssetPath();



        return [$url, $assetWarning];
    }


}
