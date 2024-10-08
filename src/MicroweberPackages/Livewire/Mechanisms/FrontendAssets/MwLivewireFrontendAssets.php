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


}
