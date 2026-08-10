<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization;

use Illuminate\Support\Facades\Blade;
use MicroweberPackages\ImageOptimization\Console\Commands\ClearWebpCacheCommand;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class ImageOptimizationServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/image-optimization');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/image-optimization.php', 'image-optimization');

        $this->app->singleton(ImageOptimizationService::class, function ($app) {
            return new ImageOptimizationService();
        });

    }

    public function packageBooted(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'image-optimization');

        $this->registerBladeDirectives();

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearWebpCacheCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../config/image-optimization.php' => config_path('image-optimization.php'),
            ], 'image-optimization-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/image-optimization'),
            ], 'image-optimization-views');
        }
    }

    protected function registerBladeDirectives(): void
    {
        $serviceClass = ImageOptimizationService::class;

        Blade::directive('optimizedImage', function (mixed $expression) use ($serviceClass) {
            $expression = is_string($expression) ? $expression : '';

            return "<?php echo app('{$serviceClass}')->getOptimizedUrl({$expression}); ?>";
        });

        Blade::directive('webpImage', function (mixed $expression) use ($serviceClass) {
            $expression = is_string($expression) ? $expression : '';

            return "<?php echo app('{$serviceClass}')->getWebpOrOriginal({$expression}); ?>";
        });

        Blade::directive('lazyImage', function (mixed $expression) use ($serviceClass) {
            $expression = is_string($expression) ? $expression : '';
            $args = $this->parseArguments($expression);
            $src = $args[0] ?? "''";
            $alt = $args[1] ?? "''";
            $attributes = $args[2] ?? '[]';

            return "<?php echo app('{$serviceClass}')->generateLazyImage({$src}, {$alt}, {$attributes}); ?>";
        });

        Blade::directive('responsiveImage', function (mixed $expression) use ($serviceClass) {
            $expression = is_string($expression) ? $expression : '';
            $args = $this->parseArguments($expression);
            $src = $args[0] ?? "''";
            $sizes = $args[1] ?? '[]';
            $alt = $args[2] ?? "''";
            $attributes = $args[3] ?? '[]';

            return "<?php echo app('{$serviceClass}')->generateResponsiveImage({$src}, {$sizes}, {$alt}, {$attributes}); ?>";
        });

        Blade::directive('webpPicture', function (mixed $expression) use ($serviceClass) {
            $expression = is_string($expression) ? $expression : '';
            $args = $this->parseArguments($expression);
            $src = $args[0] ?? "''";
            $alt = $args[1] ?? "''";
            $attributes = $args[2] ?? '[]';

            return $this->generateWebpPictureDirective($src, $alt, $attributes, $serviceClass);
        });

        Blade::directive('lazyCss', function () {
            return '<style>' . $this->getLazyLoadingCss() . '</style>';
        });

        Blade::directive('lazyJs', function () {
            return '<script>' . $this->getLazyLoadingJs() . '</script>';
        });
    }

    /**
     * @return list<string>
     */
    protected function parseArguments(string $expression): array
    {
        $args = [];
        $current = '';
        $depth = 0;
        $inQuote = false;
        $quoteChar = '';

        $length = strlen($expression);
        for ($i = 0; $i < $length; $i++) {
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

    protected function generateWebpPictureDirective(string $src, string $alt, string $attributes, string $serviceClass): string
    {
        return "<?php
            \$__service = app('{$serviceClass}');
            \$__src = {$src};
            \$__alt = {$alt};
            \$__attrs = {$attributes};
            \$__webpSrc = \$__service->getWebpOrOriginal(\$__src);

            echo '<picture>';
            if (\$__webpSrc !== \$__src) {
                echo '<source srcset=\"' . htmlspecialchars(\$__webpSrc) . '\" type=\"image/webp\">';
            }
            echo '<img src=\"' . htmlspecialchars(\$__src) . '\"';
            echo ' alt=\"' . htmlspecialchars((string) \$__alt) . '\"';
            echo ' loading=\"lazy\"';
            echo ' decoding=\"async\"';
            if (is_array(\$__attrs)) {
                foreach (\$__attrs as \$key => \$value) {
                    echo ' ' . \$key . '=\"' . htmlspecialchars((string) \$value) . '\"';
                }
            }
            echo '>';
            echo '</picture>';
        ?>";
    }

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
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

@media (prefers-color-scheme: dark) {
    .mw-lazy-placeholder {
        background-color: #374151;
        background-image: linear-gradient(90deg, #374151 0%, #4b5563 50%, #374151 100%);
    }
}

@media (prefers-reduced-motion: reduce) {
    .mw-lazy-image { transition: none; }
    .mw-lazy-placeholder { animation: none; background-image: none; }
}
';
    }

    protected function getLazyLoadingJs(): string
    {
        return '
(function() {
    "use strict";

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
            images.forEach(loadImage);
        }
    }

    function loadImage(img) {
        const src = img.getAttribute("data-src");
        if (!src) return;

        img.src = src;
        img.removeAttribute("data-src");
        img.classList.add("mw-lazy-loaded");

        img.onload = function() {
            img.classList.add("mw-lazy-loaded");
        };

        img.onerror = function() {
            console.warn("Failed to load lazy image:", src);
            img.classList.add("mw-lazy-error");
        };
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initLazyLoading);
    } else {
        initLazyLoading();
    }

    window.mwInitLazyLoading = initLazyLoading;
})();
';
    }
}
