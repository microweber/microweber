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

        return <<<HTML

    const Ziggy = {$payload->toJson()};

    $routeFunction

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
        return __DIR__ . '/../../../../../vendor/tightenco/ziggy/dist/index.js';
    }

    private function getRouteFunction()
    {

        return file_get_contents($this->getRouteFilePath());
    }
}
