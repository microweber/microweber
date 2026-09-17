<?php

namespace Modules\Pictures\Tests\Unit;

use Modules\Pictures\Microweber\PicturesModule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-09-17 — Thumbnail crop across every Pictures skin.
 *
 * The media browser stores a per-image focal point in image_options
 * (crop = center|top|custom, + crop-position "x% y%"). PicturesModule::
 * withCropPositions() normalises that to a single `crop_position` CSS string
 * per item, and every skin applies it as object-position / background-position.
 * These tests guard both halves.
 */
class PicturesCropTest extends TestCase
{
    #[Test]
    public function with_crop_positions_maps_each_crop_mode(): void
    {
        $pictures = [
            ['id' => 1, 'image_options' => ['crop' => 'center']],
            ['id' => 2, 'image_options' => ['crop' => 'top']],
            ['id' => 3, 'image_options' => ['crop' => 'custom', 'crop-position' => '40% 65%']],
            ['id' => 4, 'image_options' => ['crop' => 'custom', 'crop-position' => '']], // custom w/o pos → center
            ['id' => 5, 'image_options' => []],                                          // nothing set → center
            ['id' => 6],                                                                 // no image_options key
        ];

        $out = PicturesModule::withCropPositions($pictures);

        $this->assertSame('', $out[0]['crop_position'], 'center → empty (CSS default)');
        $this->assertSame('center top', $out[1]['crop_position'], 'top → center top');
        $this->assertSame('40% 65%', $out[2]['crop_position'], 'custom → stored position');
        $this->assertSame('', $out[3]['crop_position'], 'custom without position → empty');
        $this->assertSame('', $out[4]['crop_position'], 'empty options → empty');
        $this->assertSame('', $out[5]['crop_position'], 'missing options → empty');
    }

    /**
     * Every skin that renders an image must wire the crop focal point. Two skins
     * are legitimately exempt: button_gallery (renders only a lightbox link, no
     * image) and default (honors crop via its own inline object-position path
     * gated on the aspect-ratio setting).
     *
     * DataProvider runs pre-boot, so it returns RELATIVE paths and resolves
     * base_path() per-test.
     */
    #[Test]
    #[DataProvider('skinFileProvider')]
    public function every_image_skin_wires_the_crop_position(string $relativePath): void
    {
        $full = base_path($relativePath);
        $this->assertFileExists($full);
        $src = file_get_contents($full);

        $this->assertStringContainsString(
            'crop_position',
            $src,
            basename($relativePath) . ' must apply $item[\'crop_position\'] (object-position / background-position)'
        );
    }

    public static function skinFileProvider(): array
    {
        // Runs pre-boot — do NOT call base_path(). __DIR__ is available; glob the
        // templates via a path relative to this test file and hand the test a
        // base_path()-relative string it resolves per-test (post-boot).
        $relDir = 'Modules/Pictures/resources/views/templates';
        $absDir = __DIR__ . '/../../resources/views/templates';
        $exempt = ['button_gallery.blade.php', 'default.blade.php'];

        $cases = [];
        foreach (glob($absDir . '/*.blade.php') as $file) {
            $name = basename($file);
            if (in_array($name, $exempt, true)) {
                continue;
            }
            $cases[$name] = [$relDir . '/' . $name];
        }

        return $cases;
    }
}
