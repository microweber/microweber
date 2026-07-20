<?php

declare(strict_types=1);

namespace MicroweberPackages\SvgIcons\Tests\Feature;

use MicroweberPackages\SvgIcons\SvgIconsServiceProvider;
use MicroweberPackages\SvgIcons\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Ensures the artisan vendor:publish command copies SVGs to the
 * public directory so they can be served via URL.
 */
class PublishTest extends TestCase
{
    #[Test]
    public function publish_copies_svg_files_to_public_vendor(): void
    {
        $targetDir = public_path('vendor/microweber-packages/svg-icons');

        // Clean up from previous runs.
        if (is_dir($targetDir)) {
            array_map('unlink', glob($targetDir . '/*.svg'));
            rmdir($targetDir);
        }

        $this->artisan('vendor:publish', [
            '--tag'   => 'mw-svg-icons',
            '--force' => true,
        ])->assertExitCode(0);

        $this->assertDirectoryExists($targetDir);

        // Spot-check a few icons exist.
        $this->assertFileExists($targetDir . '/text.svg');
        $this->assertFileExists($targetDir . '/checkbox.svg');
        $this->assertFileExists($targetDir . '/no-content.svg');
    }

    #[Test]
    public function published_icon_count_matches_source(): void
    {
        $this->artisan('vendor:publish', [
            '--tag'   => 'mw-svg-icons',
            '--force' => true,
        ])->assertExitCode(0);

        $targetDir  = public_path('vendor/microweber-packages/svg-icons');
        $published  = glob($targetDir . '/*.svg');
        $source     = SvgIconsServiceProvider::availableIcons();

        $this->assertCount(
            count($source),
            $published,
            'Published SVG count does not match source icon count.'
        );
    }
}
