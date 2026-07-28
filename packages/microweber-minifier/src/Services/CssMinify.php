<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Services;

use MicroweberPackages\Minifier\Minifiers\CssMinifier;
use Throwable;

/**
 * High-level CSS minification API.
 */
class CssMinify
{
    /**
     * @param  array<string, mixed>  $options
     */
    public function minify(string $css, array $options = []): string
    {
        if ($css === '') {
            return '';
        }

        $merged = array_merge($this->defaultOptions(), $options);

        try {
            return CssMinifier::minify($css, $merged);
        } catch (Throwable) {
            return $css;
        }
    }

    /**
     * @param  array<string, mixed>  $options
     *
     * @throws Throwable
     */
    public function minifyOrFail(string $css, array $options = []): string
    {
        $merged = array_merge($this->defaultOptions(), $options);

        return CssMinifier::minify($css, $merged);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultOptions(): array
    {
        try {
            /** @var array<string, mixed>|null $cfg */
            $cfg = config('minifier.css');
            if (is_array($cfg)) {
                return $cfg;
            }
        } catch (Throwable) {
            // standalone / no container
        }

        return [
            'remove_comments' => true,
            'shorten_zeros' => true,
        ];
    }
}
