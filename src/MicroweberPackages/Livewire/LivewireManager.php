<?php

namespace MicroweberPackages\Livewire;

use \Livewire\LivewireManager as BaseLivewireManager;

class LivewireManager extends BaseLivewireManager
{
    protected function copyAssets()
    {
        $livewireCacheFolder = base_path() . '/userfiles/cache/livewire/' . \MicroweberPackages\App\LaravelApplication::APP_VERSION . '/livewire/';
        $livewireCacheFolder = normalize_path($livewireCacheFolder,true);

        if (!is_dir($livewireCacheFolder)) {
            mkdir_recursive($livewireCacheFolder);
        }

        $livewireCachedFile = $livewireCacheFolder . '/livewire.js';
        $livewireCachedFileMap = $livewireCacheFolder . '/livewire.js.map';
        $livewireCachedFileManifest = $livewireCacheFolder . '/manifest.json';

        if (!is_file($livewireCachedFile)) {

            $livewireOriginalFile = realpath(__DIR__ . '/../../../vendor/livewire/livewire/dist/livewire.js');
            if (!is_file($livewireOriginalFile)) {
                throw new \Exception('livewire.js not exist in vendor');
            }

            copy($livewireOriginalFile, $livewireCachedFile);


            $livewireOriginalFileMap = realpath(__DIR__ . '/../../../vendor/livewire/livewire/dist/livewire.js.map');
            if (!is_file($livewireOriginalFileMap)) {
                throw new \Exception('livewire.js.map not exist in vendor');
            }

            copy($livewireOriginalFileMap, $livewireCachedFileMap);

            if (!is_file($livewireCachedFileManifest)) {
                $livewireOriginalFileManifest = realpath(__DIR__ . '/../../../vendor/livewire/livewire/dist/manifest.json');
                copy($livewireOriginalFileManifest, $livewireCachedFileManifest);
            }
        }
    }
    protected function getAssetPath()
    {
        $livewireCacheFolder = 'userfiles/cache/livewire/' . \MicroweberPackages\App\LaravelApplication::APP_VERSION . '/livewire/livewire.js';
        return site_url($livewireCacheFolder);

    }

    protected function javaScriptAssets($options)
    {
        $this->copyAssets();

        $assetsUrl = config('livewire.asset_url') ?: rtrim($options['asset_url'] ?? '', '/');

        $appUrl = config('livewire.app_url')
            ?: rtrim($options['app_url'] ?? '', '/')
                ?: $assetsUrl;

        $jsLivewireToken = app()->has('session.store') ? "'" . csrf_token() . "'" : 'null';

        $jsonEncodedOptions = $options ? json_encode($options) : '';


        $fullAssetPath = $this->getAssetPath();

        $assetWarning = null;

        $nonce = isset($options['nonce']) ? "nonce=\"{$options['nonce']}\"" : '';


        $devTools = null;
        $windowLivewireCheck = null;
        $windowAlpineCheck = null;


        // Adding semicolons for this JavaScript is important,
        // because it will be minified in production.
        return <<<HTML
{$assetWarning}
<script src="{$fullAssetPath}" data-turbo-eval="false" data-turbolinks-eval="false" {$nonce}></script>
<script data-turbo-eval="false" data-turbolinks-eval="false" {$nonce}>
    {$windowLivewireCheck}

    window.livewire = new Livewire({$jsonEncodedOptions});
    {$devTools}
    window.Livewire = window.livewire;
    window.livewire_app_url = '{$appUrl}';
    window.livewire_token = {$jsLivewireToken};

	{$windowAlpineCheck}
    window.deferLoadingAlpine = function (callback) {
        window.addEventListener('livewire:load', function () {
            callback();
        });
    };

    let started = false;

    window.addEventListener('alpine:initializing', function () {
        if (! started) {
            window.livewire.start();

            started = true;
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        if (! started) {
            window.livewire.start();

            started = true;
        }
    });
</script>
HTML;
    }

}
