<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\View\Blade;

use Illuminate\Support\Facades\Blade;
use MicroweberPackages\Cache\Services\FragmentCacheService;

/**
 * Blade Fragment Cache Directive
 * 
 * Provides @fragment and @endfragment Blade directives for caching
 * sections of views.
 * 
 * Usage:
 *   @fragment('menu', ['menu', 'navigation'], 3600)
 *     {{-- Expensive menu rendering here --}}
 *   @endfragment
 * 
 * @package MicroweberPackages\Cache\View\Blade
 */
class FragmentCacheDirective
{
    /**
     * Register the Blade directives.
     */
    public static function register(): void
    {
        Blade::directive('fragment', function ($expression) {
            // Parse expression: 'key', ['tags'], ttl
            $parts = self::parseExpression($expression);
            $key = $parts['key'] ?? "'fragment_' . \$__env->getCurrentComponentIndex()";
            $tags = $parts['tags'] ?? "['fragment']";
            $ttl = $parts['ttl'] ?? 'null';
            
            return "<?php
                \$__fragmentCacheService = app(\MicroweberPackages\Cache\Services\FragmentCacheService::class);
                \$__fragmentKey = {$key};
                \$__fragmentTags = {$tags};
                \$__fragmentTtl = {$ttl};
                
                \$__cachedFragment = \$__fragmentCacheService->get(\$__fragmentKey, \$__fragmentTags, \$__fragmentTtl);
                
                if (\$__cachedFragment !== null) {
                    echo \$__cachedFragment['content'];
                } else {
                    ob_start();
                    \$__fragmentCaching = true;
            ?>";
        });

        Blade::directive('endfragment', function () {
            return "<?php
                if (isset(\$__fragmentCaching) && \$__fragmentCaching) {
                    \$__fragmentContent = ob_get_clean();
                    \$__fragmentCacheService->store(\$__fragmentKey, \$__fragmentContent, \$__fragmentTags, \$__fragmentTtl);
                    echo \$__fragmentContent;
                    unset(\$__fragmentCaching);
                }
                
                unset(\$__fragmentCacheService, \$__fragmentKey, \$__fragmentTags, \$__fragmentTtl, \$__cachedFragment, \$__fragmentContent);
            ?>";
        });

        // Alias directives
        Blade::directive('cache', function ($expression) {
            return "@fragment({$expression})";
        });

        Blade::directive('endcache', function () {
            return "@endfragment";
        });

        // Menu fragment directive
        Blade::directive('menuCache', function ($expression) {
            return "<?php
                \$__fragmentCacheService = app(\MicroweberPackages\Cache\Services\FragmentCacheService::class);
                echo \$__fragmentCacheService->menu({$expression}, function() {
                    ob_start();
            ?>";
        });

        Blade::directive('endmenuCache', function () {
            return "<?php
                    return ob_get_clean();
                });
            ?>";
        });

        // Module fragment directive
        Blade::directive('moduleCache', function ($expression) {
            return "<?php
                \$__fragmentCacheService = app(\MicroweberPackages\Cache\Services\FragmentCacheService::class);
                echo \$__fragmentCacheService->module({$expression}, function() {
                    ob_start();
            ?>";
        });

        Blade::directive('endmoduleCache', function () {
            return "<?php
                    return ob_get_clean();
                });
            ?>";
        });
    }

    /**
     * Parse the Blade directive expression.
     */
    protected static function parseExpression(string $expression): array
    {
        // Remove outer parentheses
        $expression = trim($expression, '()');
        
        // Split by commas, but respect quoted strings
        $parts = str_getcsv($expression, ',', "'");
        
        $result = [];
        
        if (isset($parts[0])) {
            $result['key'] = trim($parts[0]);
        }
        
        if (isset($parts[1])) {
            $result['tags'] = trim($parts[1]);
        }
        
        if (isset($parts[2])) {
            $result['ttl'] = trim($parts[2]);
        }
        
        return $result;
    }
}
