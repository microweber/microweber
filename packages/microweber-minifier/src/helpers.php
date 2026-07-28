<?php

declare(strict_types=1);

use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Services\MinifierService;

if (!function_exists('js_minify')) {
    /**
     * Minify JavaScript source.
     *
     * @param  array<string, mixed>  $options
     */
    function js_minify(string $js, array $options = []): string
    {
        try {
            return app(JsMinify::class)->minify($js, $options);
        } catch (Throwable) {
            return app(MinifierService::class)->minifyJs($js, $options);
        }
    }
}

if (!function_exists('css_minify')) {
    /**
     * Minify CSS source.
     *
     * @param  array<string, mixed>  $options
     */
    function css_minify(string $css, array $options = []): string
    {
        try {
            return app(CssMinify::class)->minify($css, $options);
        } catch (Throwable) {
            return app(MinifierService::class)->minifyCss($css, $options);
        }
    }
}

if (!function_exists('minify_js')) {
    /**
     * Alias of js_minify() for backwards compatibility with CMS callers.
     *
     * @param  array<string, mixed>  $options
     */
    function minify_js(string $js, array $options = []): string
    {
        return js_minify($js, $options);
    }
}

if (!function_exists('minify_css')) {
    /**
     * Alias of css_minify() for backwards compatibility with CMS callers.
     *
     * @param  array<string, mixed>  $options
     */
    function minify_css(string $css, array $options = []): string
    {
        return css_minify($css, $options);
    }
}

if (!function_exists('minifier_stats')) {
    /**
     * @return array<string, mixed>
     */
    function minifier_stats(): array
    {
        return app(MinifierService::class)->getStatistics();
    }
}

if (!function_exists('minifier_enabled')) {
    function minifier_enabled(): bool
    {
        return app(MinifierService::class)->isEnabled();
    }
}
