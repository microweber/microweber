<?php

declare(strict_types=1);

namespace MicroweberPackages\SvgIcons\Tests\Unit;

use MicroweberPackages\SvgIcons\SvgIconsServiceProvider;
use MicroweberPackages\SvgIcons\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SvgIconsServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_the_mw_icon_set(): void
    {
        // The Factory should have the 'mw' set registered after boot.
        $factory = $this->app->make(\BladeUI\Icons\Factory::class);

        // Factory::all() returns an array keyed by set name.
        $sets = $factory->all();
        $this->assertArrayHasKey('mw', $sets);
    }

    #[Test]
    public function available_icons_returns_non_empty_list(): void
    {
        $icons = SvgIconsServiceProvider::availableIcons();

        $this->assertNotEmpty($icons, 'The package must ship at least one SVG icon.');
        $this->assertContains('text', $icons);
        $this->assertContains('checkbox', $icons);
        $this->assertContains('dropdown', $icons);
        $this->assertContains('email', $icons);
        $this->assertContains('numbers', $icons);
    }

    #[Test]
    public function all_icon_files_use_dash_naming(): void
    {
        $icons = SvgIconsServiceProvider::availableIcons();

        foreach ($icons as $icon) {
            $this->assertStringNotContainsString(
                '_',
                $icon,
                "Icon '{$icon}' contains an underscore — all names must use dashes."
            );
            // Verify no uppercase (camelCase normalisation)
            $this->assertSame(
                strtolower($icon),
                $icon,
                "Icon '{$icon}' contains uppercase — all names must be lowercase kebab-case."
            );
        }
    }

    #[Test]
    public function svg_path_points_to_existing_directory(): void
    {
        $provider = new SvgIconsServiceProvider($this->app);
        $this->assertDirectoryExists($provider->svgPath());
    }

    #[Test]
    public function every_svg_file_is_valid_xml(): void
    {
        $dir = (new SvgIconsServiceProvider($this->app))->svgPath();

        foreach (glob($dir . '/*.svg') as $file) {
            $content = file_get_contents($file);
            $this->assertNotFalse($content, "Could not read {$file}");

            // Suppress XML warnings and check parse result.
            $prev = libxml_use_internal_errors(true);
            $doc = simplexml_load_string($content);
            libxml_use_internal_errors($prev);

            $this->assertNotFalse(
                $doc,
                sprintf('Icon "%s" is not valid XML/SVG.', basename($file))
            );
        }
    }
}
