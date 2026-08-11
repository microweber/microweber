<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

/**
 * Optional URL helpers on the registry.
 *
 * Soft-depends on microweber-packages/url (`UrlManager` facade). Falls back
 * to Laravel `url()` / `request()->getHost()` when available.
 *
 * @deprecated Prefer UrlManager / Laravel URL helpers directly.
 */
trait ManagesUrl
{
    public function siteUrl(string|false $path = false): string
    {
        $facade = \MicroweberPackages\Url\Facades\UrlManager::class;
        if (class_exists($facade)) {
            /** @var string $result */
            $result = $facade::site($path === false ? false : $path);

            return $result;
        }

        if (function_exists('url')) {
            return $path === false ? (string) url('/') : (string) url((string) $path);
        }

        return $path === false ? '/' : '/' . ltrim((string) $path, '/');
    }

    public function siteHostname(): string
    {
        $facade = \MicroweberPackages\Url\Facades\UrlManager::class;
        if (class_exists($facade)) {
            /** @var string $result */
            $result = $facade::hostname();

            return $result;
        }

        if (function_exists('request')) {
            try {
                return (string) request()->getHost();
            } catch (\Throwable) {
                // fall through
            }
        }

        return '';
    }
}
