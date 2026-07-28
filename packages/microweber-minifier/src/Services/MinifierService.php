<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Services;

use Throwable;

/**
 * Facade-friendly service exposing JS + CSS minification and package status.
 */
class MinifierService
{
    public function __construct(
        protected JsMinify $jsMinify,
        protected CssMinify $cssMinify,
    ) {
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function minifyJs(string $js, array $options = []): string
    {
        if (!$this->isJsEnabled()) {
            return $js;
        }

        return $this->jsMinify->minify($js, $options);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function minifyCss(string $css, array $options = []): string
    {
        if (!$this->isCssEnabled()) {
            return $css;
        }

        return $this->cssMinify->minify($css, $options);
    }

    public function isEnabled(): bool
    {
        return (bool) $this->config('enabled', true);
    }

    public function isJsEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->config('minify_js', true);
    }

    public function isCssEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->config('minify_css', true);
    }

    /**
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'minify_js' => $this->isJsEnabled(),
            'minify_css' => $this->isCssEnabled(),
            'js_flagged_comments' => (bool) $this->config('js.flaggedComments', false),
            'css_remove_comments' => (bool) $this->config('css.remove_comments', true),
            'version' => '1.0.0',
            'engine' => [
                'js' => 'JsMinifier (JShrink-based)',
                'css' => 'CssMinifier',
            ],
        ];
    }

    /**
     * Smoke-test minification with sample payloads.
     *
     * @return array{js: array{ok: bool, original_len: int, minified_len: int, ratio: float}, css: array{ok: bool, original_len: int, minified_len: int, ratio: float}}
     */
    public function selfTest(): array
    {
        $jsSample = "function hello(name) {\n    // comment\n    return 'Hello ' + name;\n}\n";
        $cssSample = "/* comment */\n.body {\n    color: red;\n    margin: 0px;\n}\n";

        $jsMin = $this->jsMinify->minify($jsSample);
        $cssMin = $this->cssMinify->minify($cssSample);

        $jsOrig = strlen($jsSample);
        $cssOrig = strlen($cssSample);
        $jsMinLen = strlen($jsMin);
        $cssMinLen = strlen($cssMin);

        return [
            'js' => [
                'ok' => $jsMin !== '' && $jsMinLen <= $jsOrig,
                'original_len' => $jsOrig,
                'minified_len' => $jsMinLen,
                'ratio' => round($jsMinLen / max(1, $jsOrig), 4),
            ],
            'css' => [
                'ok' => $cssMin !== '' && $cssMinLen <= $cssOrig,
                'original_len' => $cssOrig,
                'minified_len' => $cssMinLen,
                'ratio' => round($cssMinLen / max(1, $cssOrig), 4),
            ],
        ];
    }

    public function getJsMinify(): JsMinify
    {
        return $this->jsMinify;
    }

    public function getCssMinify(): CssMinify
    {
        return $this->cssMinify;
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        try {
            return config('minifier.' . $key, $default);
        } catch (Throwable) {
            return $default;
        }
    }
}
