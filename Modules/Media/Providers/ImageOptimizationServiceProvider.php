<?php

namespace Modules\Media\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Media\Services\ImageOptimizationService;

class ImageOptimizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageOptimizationService::class, function ($app) {
            return new ImageOptimizationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    /**
     * Register Blade directives for image optimization.
     */
    protected function registerBladeDirectives(): void
    {
        // @optimizedImage($src, $width, $height) - Get optimized image URL
        Blade::directive('optimizedImage', function ($expression) {
            return "<?php echo app(\Modules\Media\Services\ImageOptimizationService::class)->getOptimizedUrl({$expression}); ?>";
        });

        // @webpImage($src) - Get WebP version of image
        Blade::directive('webpImage', function ($expression) {
            return "<?php echo app(\Modules\Media\Services\ImageOptimizationService::class)->getWebpOrOriginal({$expression}); ?>";
        });

        // @lazyImage($src, $alt, $attributes) - Generate lazy loading img tag
        Blade::directive('lazyImage', function ($expression) {
            // Parse the expression to handle multiple arguments
            $args = $this->parseArguments($expression);
            $src = $args[0] ?? "''";
            $alt = $args[1] ?? "''";
            $attributes = $args[2] ?? '[]';

            return "<?php echo app(\Modules\Media\Services\ImageOptimizationService::class)->generateLazyImage({$src}, {$alt}, {$attributes}); ?>";
        });

        // @responsiveImage($src, $sizes, $alt, $attributes) - Generate responsive img tag with srcset
        Blade::directive('responsiveImage', function ($expression) {
            $args = $this->parseArguments($expression);
            $src = $args[0] ?? "''";
            $sizes = $args[1] ?? '[]';
            $alt = $args[2] ?? "''";
            $attributes = $args[3] ?? '[]';

            return "<?php echo app(\Modules\Media\Services\ImageOptimizationService::class)->generateResponsiveImage({$src}, {$sizes}, {$alt}, {$attributes}); ?>";
        });

        // @webpPicture($src, $alt, $attributes) - Generate picture element with WebP and fallback
        Blade::directive('webpPicture', function ($expression) {
            $args = $this->parseArguments($expression);
            $src = $args[0] ?? "''";
            $alt = $args[1] ?? "''";
            $attributes = $args[2] ?? '[]';

            return $this->generateWebpPictureDirective($src, $alt, $attributes);
        });

        // @lazyCss - Include lazy loading CSS
        Blade::directive('lazyCss', function () {
            return '<style>' . $this->getLazyLoadingCss() . '</style>';
        });

        // @lazyJs - Include lazy loading JavaScript
        Blade::directive('lazyJs', function () {
            return '<script>' . $this->getLazyLoadingJs() . '</script>';
        });
    }

    /**
     * Parse directive arguments.
     *
     * @param string $expression
     * @return array
     */
    protected function parseArguments(string $expression): array
    {
        $args = [];
        $current = '';
        $depth = 0;
        $inQuote = false;
        $quoteChar = '';

        for ($i = 0; $i < strlen($expression); $i++) {
            $char = $expression[$i];

            if (!$inQuote && ($char === '"' || $char === "'")) {
                $inQuote = true;
                $quoteChar = $char;
            } elseif ($inQuote && $char === $quoteChar) {
                $inQuote = false;
            } elseif (!$inQuote && ($char === '(' || $char === '[' || $char === '{')) {
                $depth++;
            } elseif (!$inQuote && ($char === ')' || $char === ']' || $char === '}')) {
                $depth--;
            } elseif (!$inQuote && $depth === 0 && $char === ',') {
                $args[] = trim($current);
                $current = '';
                continue;
            }

            $current .= $char;
        }

        if (trim($current) !== '') {
            $args[] = trim($current);
        }

        return $args;
    }

    /**
     * Generate WebP picture directive HTML.
     *
     * @param string $src
     * @param string $alt
     * @param string $attributes
     * @return string
     */
    protected function generateWebpPictureDirective(string $src, string $alt, string $attributes): string
    {
        $service = ImageOptimizationService::class;

        return "<?php
            \$__service = app({$service});
            \$__src = {$src};
            \$__alt = {$alt};
            \$__attrs = {$attributes};
            \$__webpSrc = \$__service->getWebpOrOriginal(\$__src);
            
            echo '<picture>';
            if (\$__webpSrc !== \$__src) {
                echo '<source srcset=\"' . htmlspecialchars(\$__webpSrc) . '\" type=\"image/webp\">';
            }
            echo '<img src=\"' . htmlspecialchars(\$__src) . '\"';
            echo ' alt=\"' . htmlspecialchars(\$__alt) . '\"';
            echo ' loading=\"lazy\"';
            echo ' decoding=\"async\"';
            foreach (\$__attrs as \$key => \$value) {
                echo ' ' . \$key . '=\"' . htmlspecialchars(\$value) . '\"';
            }
            echo '>';
            echo '</picture>';
        ?>";
    }

    /**
     * Get lazy loading CSS.
     *
     * @return string
     */
    protected function getLazyLoadingCss(): string
    {
        return '
/* Lazy Loading Styles */
.mw-lazy-image {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.mw-lazy-image.mw-lazy-loaded {
    opacity: 1;
}

.mw-lazy-placeholder {
    background-color: #f3f4f6;
    background-image: linear-gradient(90deg, #f3f4f6 0%, #e5e7eb 50%, #f3f4f6 100%);
    background-size: 200% 100%;
    animation: mw-lazy-shimmer 1.5s infinite;
}

@keyframes mw-lazy-shimmer {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .mw-lazy-placeholder {
        background-color: #374151;
        background-image: linear-gradient(90deg, #374151 0%, #4b5563 50%, #374151 100%);
    }
}

/* Respect prefers-reduced-motion */
@media (prefers-reduced-motion: reduce) {
    .mw-lazy-image {
        transition: none;
    }
    .mw-lazy-placeholder {
        animation: none;
        background-image: none;
    }
}
';
    }

    /**
     * Get lazy loading JavaScript.
     *
     * @return string
     */
    protected function getLazyLoadingJs(): string
    {
        return '
(function() {
    "use strict";
    
    // Lazy loading with Intersection Observer
    function initLazyLoading() {
        const images = document.querySelectorAll("img[data-src]");
        
        if ("IntersectionObserver" in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        loadImage(img);
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: "50px 0px",
                threshold: 0.01
            });
            
            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for browsers without Intersection Observer
            images.forEach(loadImage);
        }
    }
    
    function loadImage(img) {
        const src = img.getAttribute("data-src");
        if (!src) return;
        
        img.src = src;
        img.removeAttribute("data-src");
        img.classList.add("mw-lazy-loaded");
        
        // Handle load event
        img.onload = function() {
            img.classList.add("mw-lazy-loaded");
            img.removeEventListener("load", img.onload);
        };
        
        // Handle error
        img.onerror = function() {
            console.warn("Failed to load lazy image:", src);
            img.classList.add("mw-lazy-error");
        };
    }
    
    // Initialize on DOM ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initLazyLoading);
    } else {
        initLazyLoading();
    }
    
    // Re-initialize for dynamically added content
    window.mwInitLazyLoading = initLazyLoading;
})();
';
    }
}
