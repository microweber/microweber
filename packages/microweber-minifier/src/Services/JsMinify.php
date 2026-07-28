<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Services;

use MicroweberPackages\Minifier\Minifiers\JsMinifier;
use Throwable;

/**
 * High-level JavaScript minification API.
 *
 * Falls back to the original input on failure so asset pipelines never break.
 */
class JsMinify
{
    /**
     * @param  array<string, mixed>  $options  JShrink-compatible options (e.g. flaggedComments)
     */
    public function minify(string $js, array $options = []): string
    {
        if ($js === '') {
            return '';
        }

        $merged = array_merge($this->defaultOptions(), $options);

        try {
            return JsMinifier::minify($js, $merged);
        } catch (Throwable) {
            return $js;
        }
    }

    /**
     * Strict minify that rethrows on failure.
     *
     * @param  array<string, mixed>  $options
     *
     * @throws Throwable
     */
    public function minifyOrFail(string $js, array $options = []): string
    {
        $merged = array_merge($this->defaultOptions(), $options);

        return JsMinifier::minify($js, $merged);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultOptions(): array
    {
        try {
            /** @var array<string, mixed>|null $cfg */
            $cfg = config('minifier.js');
            if (is_array($cfg)) {
                return $cfg;
            }
        } catch (Throwable) {
            // standalone / no container
        }

        return ['flaggedComments' => false];
    }
}
