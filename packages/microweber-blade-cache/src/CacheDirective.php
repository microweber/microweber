<?php

declare(strict_types=1);

namespace MicroweberPackages\BladeCache;

use Illuminate\Support\Facades\Blade;

/**
 * Registers the @cache / @endcache Blade directives.
 *
 * Usage:
 *   @cache('sidebar', ['navigation'], 3600)
 *       {{-- expensive HTML --}}
 *   @endcache
 *
 * Arguments (all optional after key):
 *   1. key   – unique cache key (string)
 *   2. tags  – array of cache tags for invalidation
 *   3. ttl   – seconds until expiry (null = config default)
 */
class CacheDirective
{
    public static function register(): void
    {
        Blade::directive('cache', function (string $expression) {
            $parts = self::parseExpression($expression);

            $key  = $parts['key']  ?? "''";
            $tags = $parts['tags'] ?? '[]';
            $ttl  = $parts['ttl']  ?? 'null';

            return "<?php
                \$__bladeCache = app(\\MicroweberPackages\\BladeCache\\BladeCacheService::class);
                \$__bladeCacheKey  = {$key};
                \$__bladeCacheTags = {$tags};
                \$__bladeCacheTtl  = {$ttl};
                \$__bladeCacheHit  = \$__bladeCache->get(\$__bladeCacheKey, \$__bladeCacheTags);
                if (\$__bladeCacheHit !== null) {
                    echo \$__bladeCacheHit;
                } else {
                    ob_start();
            ?>";
        });

        Blade::directive('endcache', function () {
            return "<?php
                    \$__bladeCacheContent = ob_get_clean();
                    \$__bladeCache->put(\$__bladeCacheKey, \$__bladeCacheContent, \$__bladeCacheTags, \$__bladeCacheTtl);
                    echo \$__bladeCacheContent;
                }
                unset(\$__bladeCache, \$__bladeCacheKey, \$__bladeCacheTags, \$__bladeCacheTtl, \$__bladeCacheHit, \$__bladeCacheContent);
            ?>";
        });
    }

    /**
     * Parse directive expression into key / tags / ttl.
     */
    protected static function parseExpression(string $expression): array
    {
        $expression = trim($expression, '() ');

        if ($expression === '') {
            return [];
        }

        // Tokenize respecting strings, arrays, and nested parens
        $tokens = self::tokenize($expression);

        $result = [];

        if (isset($tokens[0])) {
            $result['key'] = trim($tokens[0]);
        }
        if (isset($tokens[1])) {
            $result['tags'] = trim($tokens[1]);
        }
        if (isset($tokens[2])) {
            $result['ttl'] = trim($tokens[2]);
        }

        return $result;
    }

    /**
     * Split a comma-separated expression while respecting brackets and quotes.
     */
    protected static function tokenize(string $expression): array
    {
        $tokens = [];
        $depth  = 0;
        $current = '';

        for ($i = 0, $len = strlen($expression); $i < $len; $i++) {
            $char = $expression[$i];

            if ($char === '[' || $char === '(') {
                $depth++;
                $current .= $char;
            } elseif ($char === ']' || $char === ')') {
                $depth--;
                $current .= $char;
            } elseif ($char === ',' && $depth === 0) {
                $tokens[] = $current;
                $current = '';
            } elseif ($char === "'" || $char === '"') {
                $quote = $char;
                $current .= $char;
                $i++;
                while ($i < $len && $expression[$i] !== $quote) {
                    if ($expression[$i] === '\\') {
                        $current .= $expression[$i];
                        $i++;
                    }
                    if ($i < $len) {
                        $current .= $expression[$i];
                    }
                    $i++;
                }
                if ($i < $len) {
                    $current .= $expression[$i]; // closing quote
                }
            } else {
                $current .= $char;
            }
        }

        if ($current !== '') {
            $tokens[] = $current;
        }

        return $tokens;
    }
}