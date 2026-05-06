<?php

namespace MicroweberPackages\Template\Adapters\RenderHelpers;


use Tightenco\Ziggy\BladeRouteGenerator;
use Tightenco\Ziggy\Ziggy;

class ZiggyInlineJsRouteGenerator extends BladeRouteGenerator
{
    public static $generated;

    public function generate($group = null, $nonce = null)
    {
        $payload = new Ziggy($group);

        $nonce = $nonce ? ' nonce="' . $nonce . '"' : '';

        if (static::$generated) {
            return $this->generateMergeJavascript(json_encode($payload->toArray()['routes']), $nonce);
        }

        $routeFunction = $this->getRouteFunction();

        static::$generated = true;

        // TICKET-G (audit-test validation pass 2026-05-06): on Filament/
        // Livewire SPA navigation, every navigation triggers a fresh PHP
        // request — `static::$generated` resets to false in the new
        // request, so the second page re-emits the full block. The
        // browser still has the first `const Ziggy = ...` from the
        // previous page in the same JS realm, and the second `const`
        // throws "Identifier 'Ziggy' has already been declared",
        // polluting the console for every admin author.
        //
        // Fix: guard the entire first-time emission so the second
        // request becomes a harmless no-op at the browser. Promote the
        // declaration to `window.Ziggy` so subsequent merge-emissions
        // (which use Object.assign(Ziggy.routes, ...)) keep working.
        $payloadJson = $payload->toJson();
        return <<<HTML

    if (typeof window.Ziggy === 'undefined') {
        window.Ziggy = {$payloadJson};
        const Ziggy = window.Ziggy;

        $routeFunction
    }

HTML;
    }

    private function generateMergeJavascript($json, $nonce)
    {
        return <<<HTML

    (function () {
        const routes = {$json};

        Object.assign(Ziggy.routes, routes);
    })();

HTML;
    }

    private function getRouteFilePath()
    {
        $ziggyFile = __DIR__ . '/../../../../../vendor/tightenco/ziggy/dist/index.js';
        $ziggyFile2 = __DIR__ . '/../../../../../vendor/microweber-deps/ziggy/dist/index.js';
        if(is_file($ziggyFile)){
            return $ziggyFile;
        }
        if(is_file($ziggyFile2)){
            return $ziggyFile2;
        }
        

     }

    private function getRouteFunction()
    {

        return file_get_contents($this->getRouteFilePath());
    }
}
