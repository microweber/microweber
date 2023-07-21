<?php

namespace MicroweberPackages\Livewire\Mechanisms\FrontendAssets;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Livewire\Drawer\Utils;
use Livewire\Mechanisms\FrontendAssets\FrontendAssets;

class MwFrontendAssets extends FrontendAssets
{

    public function boot()
    {

        Blade::directive('livewireScripts', [static::class, 'livewireScripts']);
        Blade::directive('livewireScriptConfig', [static::class, 'livewireScriptConfig']);
        Blade::directive('livewireStyles', [static::class, 'livewireStyles']);

        $this->copyAssets();
    }
    protected function copyAssets()
    {
        $livewireCacheFolder = base_path() . '/userfiles/cache/livewire/' . \MicroweberPackages\App\LaravelApplication::APP_VERSION . '/';
        $livewireCacheFolder = normalize_path($livewireCacheFolder,true);

        if (!is_dir($livewireCacheFolder)) {
            mkdir_recursive($livewireCacheFolder);
        }

        $livewireCachedFile = $livewireCacheFolder . '/livewire.js';
        $livewireCachedFileMin = $livewireCacheFolder . '/livewire.min.js';
        $livewireCachedFileEsm = $livewireCacheFolder . '/livewire.esm.js';
         $livewireCachedFileManifest = $livewireCacheFolder . '/manifest.json';

        if (!is_file($livewireCachedFile)) {

            $livewireOriginalFile = realpath(__DIR__ . '/../../../../../vendor/livewire/livewire/dist/livewire.js');
            if (!is_file($livewireOriginalFile)) {
                throw new \Exception('livewire.js not exist in vendor');
            }

            copy($livewireOriginalFile, $livewireCachedFile);


            $livewireOriginalFile = realpath(__DIR__ . '/../../../../../vendor/livewire/livewire/dist/livewire.min.js');
            if (!is_file($livewireOriginalFile)) {
                throw new \Exception('livewire.min.js not exist in vendor');
            }

            copy($livewireOriginalFile, $livewireCachedFileMin);

            $livewireOriginalFile = realpath(__DIR__ . '/../../../../../vendor/livewire/livewire/dist/livewire.esm.js');
            if (!is_file($livewireOriginalFile)) {
                throw new \Exception('livewire.esm.js not exist in vendor');
            }

            copy($livewireOriginalFile, $livewireCachedFileEsm);


            if (!is_file($livewireCachedFileManifest)) {
                $livewireOriginalFileManifest = realpath(__DIR__ . '/../../../../../vendor/livewire/livewire/dist/manifest.json');
                copy($livewireOriginalFileManifest, $livewireCachedFileManifest);
            }
        }
    }
    public static function livewireScripts($expression)
    {

        return '{!! \MicroweberPackages\Livewire\Mechanisms\FrontendAssets\MwFrontendAssets::scripts('.$expression.') !!}';
    }

    public static function livewireScriptConfig($expression)
    {

        return '{!! \MicroweberPackages\Livewire\Mechanisms\FrontendAssets\MwFrontendAssets::scriptConfig('.$expression.') !!}';
    }

    public static function livewireStyles($expression)
    {

        return '{!! \MicroweberPackages\Livewire\Mechanisms\FrontendAssets\MwFrontendAssets::styles('.$expression.') !!}';
    }
    public static function scripts($options = [])
    {


        app(static::class)->hasRenderedScripts = true;

        $debug = config('app.debug');

        $scripts = static::js($options);

        // HTML Label.
        $html = $debug ? ['<!-- Livewire Scripts -->'] : [];

        $html[] = $scripts;

        return implode("\n", $html);
    }
    public static function js($options)
    {

        // Use the configured one...
        $url = config('livewire.asset_url');

        // Use the legacy passed in one...
        $url = $options['asset_url'] ?? $url;

        // Use the new passed in one...
        $url = $options['url'] ?? $url;

        $url = rtrim($url, '/');


        $token = app()->has('session.store') ? csrf_token() : '';

        $nonce = isset($options['nonce']) ? "nonce=\"{$options['nonce']}\"" : '';

        $updateUri = app('livewire')->getUpdateUri();

        $extraAttributes = Utils::stringifyHtmlAttributes(
            app(static::class)->scriptTagAttributes,
        );

        return <<<HTML
        <script src="{$url}" {$nonce} data-csrf="{$token}" data-uri="{$updateUri}" {$extraAttributes}></script>
        HTML;
    }
}
