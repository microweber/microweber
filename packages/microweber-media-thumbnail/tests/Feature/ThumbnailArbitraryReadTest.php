<?php

namespace MicroweberPackages\MediaThumbnail\Tests\Feature;

use MicroweberPackages\MediaThumbnail\Tests\TestCase;

/**
 * Regression guard for the unauthenticated arbitrary file read (CWE-22).
 *
 * The public GET /thumbnail_img route (web middleware, no auth) served whatever
 * file `src` pointed at: resolveSourcePath() did `if (is_file($src)) return $src;`
 * (any absolute path) and fed the raw value to public_path()/storage_path()
 * (no ".." stripping). For an `.svg` the generator returned the path verbatim
 * and serveFile() streamed its contents. So `?src=/path/any.svg` or
 * `?src=../../x.svg` leaked arbitrary server-readable files to anyone.
 *
 * `src` is now realpath-contained to the public web root / public storage disk.
 * Rejected sources fall back to the generic placeholder image, never the target
 * file, so these tests assert the secret marker is NEVER present in the body.
 */
class ThumbnailArbitraryReadTest extends TestCase
{
    private string $secretPath = '';

    private const SECRET = 'SECRET-THUMB-LEAK-MARKER-7k2';

    protected function setUp(): void
    {
        parent::setUp();
        $this->secretPath = rtrim(sys_get_temp_dir(), '/') . '/mw_thumb_secret_' . uniqid() . '.svg';
        file_put_contents($this->secretPath, '<svg>' . self::SECRET . '</svg>');
    }

    protected function tearDown(): void
    {
        if ($this->secretPath !== '' && is_file($this->secretPath)) {
            @unlink($this->secretPath);
        }
        parent::tearDown();
    }

    public function test_does_not_leak_an_absolute_path_outside_the_web_roots(): void
    {
        $response = $this->get(route('media-thumbnail.thumbnail', ['src' => $this->secretPath]));

        $this->assertStringNotContainsString(
            self::SECRET,
            (string) $response->getContent(),
            'absolute path outside the web roots must not be served'
        );
    }

    public function test_does_not_leak_via_dot_dot_traversal(): void
    {
        $response = $this->get(route('media-thumbnail.thumbnail', [
            'src' => '../../../../../..' . $this->secretPath,
        ]));

        $this->assertStringNotContainsString(self::SECRET, (string) $response->getContent());
    }

    public function test_still_serves_a_legitimate_in_root_svg(): void
    {
        // A file genuinely under the public web root must still be served
        // (containment allows it) — proves the fix didn't break real thumbnails.
        $marker = 'LEGIT-IN-ROOT-SVG-OK';
        $rel = 'mw_thumb_legit_' . uniqid() . '.svg';
        $full = public_path($rel);
        @file_put_contents($full, '<svg>' . $marker . '</svg>');

        try {
            $response = $this->get(route('media-thumbnail.thumbnail', ['src' => $rel]));
            $response->assertStatus(200);
            $this->assertStringContainsString(
                $marker,
                (string) $response->getContent(),
                'a real SVG under public_path must still be served'
            );
        } finally {
            @unlink($full);
        }
    }
}
