<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Feature;

use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use MicroweberPackages\ImageOptimization\Tests\TestCase;

/**
 * Regression guard for the unauthenticated arbitrary file read (CWE-22).
 *
 * The public GET /image-optimization/webp route served whatever file `src`
 * pointed at: serveWebp() only blocked "..", and resolveFullPath() returned any
 * absolute path verbatim when `is_file($path)` was true. So `?src=/etc/passwd`
 * or `?src=<app>/.env` — no ".." required — streamed the file to an anonymous
 * caller. `src` is now treated strictly as a path relative to the public web
 * root / public storage disk, realpath-contained to those roots.
 */
class ImageOptimizationArbitraryReadTest extends TestCase
{
    private string $secretPath = '';

    private const SECRET = 'TOP-SECRET-IMG-OPT-ABCXYZ';

    protected function setUp(): void
    {
        parent::setUp();
        // A "secret" file well outside the public web root / storage public disk.
        $this->secretPath = rtrim(sys_get_temp_dir(), '/') . '/mw_imgopt_secret_' . uniqid() . '.txt';
        file_put_contents($this->secretPath, self::SECRET);
    }

    protected function tearDown(): void
    {
        if ($this->secretPath !== '' && is_file($this->secretPath)) {
            @unlink($this->secretPath);
        }
        parent::tearDown();
    }

    public function test_webp_route_does_not_serve_an_absolute_filesystem_path(): void
    {
        $response = $this->get(route('image-optimization.webp', ['src' => $this->secretPath]));

        $this->assertNotEquals(200, $response->getStatusCode(), 'absolute path must not be served');
        $this->assertStringNotContainsString(self::SECRET, (string) $response->getContent());
    }

    public function test_webp_route_does_not_serve_etc_passwd(): void
    {
        // The exact shape from the researcher-style PoC: absolute, no "..".
        $response = $this->get(route('image-optimization.webp', ['src' => '/etc/passwd']));

        $this->assertNotEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString('root:', (string) $response->getContent());
    }

    public function test_resolve_full_path_rejects_absolute_and_traversal(): void
    {
        $service = new ImageOptimizationService(['disk' => 'public']);

        $this->assertSame('', $service->resolveFullPath($this->secretPath), 'absolute path must not resolve');
        $this->assertSame('', $service->resolveFullPath('/etc/passwd'));
        $this->assertSame('', $service->resolveFullPath('../../../../etc/passwd'));
        $this->assertSame('', $service->resolveFullPath("foo\0.jpg"));
    }

    public function test_resolve_full_path_still_resolves_a_legitimate_public_file(): void
    {
        $service = new ImageOptimizationService(['disk' => 'public']);

        $rel = 'mw_imgopt_legit_' . uniqid() . '.txt';
        $full = public_path($rel);
        @file_put_contents($full, 'ok');

        try {
            $resolved = $service->resolveFullPath($rel);
            $this->assertNotSame('', $resolved, 'a real file under public_path must resolve');
            $this->assertStringContainsString($rel, $resolved);
            // Leading slash is tolerated and still contained.
            $this->assertNotSame('', $service->resolveFullPath('/' . $rel));
        } finally {
            @unlink($full);
        }
    }
}
