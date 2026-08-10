<?php

declare(strict_types=1);

namespace MicroweberPackages\SvgIcons;

use BladeUI\Icons\Factory;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
/**
 * Registers the "mw" Blade-Icons set and publishes the SVG files
 * to the public vendor directory so they can be served via URL.
 */
class SvgIconsServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/svg-icons');
    }

    public function packageRegistered(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory): void {
            $factory->add('mw', [
                'path'   => $this->svgPath(),
                'prefix' => 'mw',
            ]);
        });
    }

    public function packageBooted(): void
    {
        // Publish SVGs so they are accessible via public URL:
        //   public/vendor/microweber-packages/svg-icons/
        $this->publishes([
            $this->svgPath() => public_path('vendor/microweber-packages/svg-icons'),
        ], ['public', 'mw-svg-icons']);
    }

    /**
     * Absolute path to the SVG icon directory shipped with this package.
     */
    public function svgPath(): string
    {
        return __DIR__ . '/../resources/svg';
    }

    /**
     * Return the list of icon names (without .svg extension) shipped
     * with this package. Useful for validation, tests, and the
     * Kitchen Sink page.
     *
     * @return list<string>
     */
    public static function availableIcons(): array
    {
        $dir = __DIR__ . '/../resources/svg';

        if (! is_dir($dir)) {
            return [];
        }

        $icons = [];
        foreach (scandir($dir) as $file) {
            if (str_ends_with($file, '.svg')) {
                $icons[] = substr($file, 0, -4);
            }
        }

        sort($icons);

        return $icons;
    }
}
