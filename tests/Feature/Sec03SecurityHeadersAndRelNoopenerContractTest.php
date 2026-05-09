<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-116 / AI-128 / SEC-03 — security headers + rel=noopener
 * regression coverage.
 *
 * Pins:
 *   - `MicroweberPackages\App\Http\Middleware\SecurityHeaders`
 *     declares CSP frame-ancestors 'self', X-Frame-Options:
 *     SAMEORIGIN, X-Content-Type-Options: nosniff, and
 *     Referrer-Policy: strict-origin-when-cross-origin.
 *   - The middleware is registered in `bootstrap/app.php`.
 *   - Every `<a target="_blank">` in `Modules/` + `src/` carries
 *     `rel="..."` containing both `noopener` AND `noreferrer`.
 *     The cycle-116 sweep landed 85 sites; the contract test
 *     enforces zero remaining gaps.
 *
 * Style after the cycle-52..115 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Sec03SecurityHeadersAndRelNoopenerContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function security_headers_middleware_declares_required_headers(): void
    {
        $src = $this->read('src/MicroweberPackages/App/Http/Middleware/SecurityHeaders.php');

        $this->assertStringContainsString(
            "frame-ancestors 'self'",
            $src,
            'SecurityHeaders: must declare CSP `frame-ancestors \'self\'`'
        );
        $this->assertStringContainsString(
            "'X-Frame-Options', 'SAMEORIGIN'",
            $src,
            'SecurityHeaders: must declare X-Frame-Options: SAMEORIGIN'
        );
        $this->assertStringContainsString(
            "'X-Content-Type-Options', 'nosniff'",
            $src,
            'SecurityHeaders: must declare X-Content-Type-Options: nosniff'
        );
        $this->assertStringContainsString(
            "'Referrer-Policy', 'strict-origin-when-cross-origin'",
            $src,
            'SecurityHeaders: must declare Referrer-Policy: strict-origin-when-cross-origin'
        );
    }

    #[Test]
    public function security_headers_middleware_is_registered_in_bootstrap(): void
    {
        $src = $this->read('bootstrap/app.php');

        $this->assertStringContainsString(
            'MicroweberPackages\\App\\Http\\Middleware\\SecurityHeaders',
            $src,
            'bootstrap/app.php: must register the SecurityHeaders middleware'
        );
    }

    #[Test]
    public function every_target_blank_anchor_has_rel_noopener_noreferrer(): void
    {
        // Walk every Blade in Modules/ + src/ and assert that any
        // <a target="_blank"> tag carries rel="..." with both
        // noopener AND noreferrer tokens.
        $offenders = [];
        $globs = [
            base_path('Modules'),
            base_path('src'),
        ];

        foreach ($globs as $base) {
            if (!is_dir($base)) {
                continue;
            }
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base));
            foreach ($rii as $file) {
                if (!$file->isFile() || !str_ends_with($file->getPathname(), '.blade.php')) {
                    continue;
                }
                $src = file_get_contents($file->getPathname());
                if (preg_match_all(
                    '/<a\\b[^>]*target=(?:"_blank"|\'_blank\')[^>]*>/s',
                    $src,
                    $matches,
                    PREG_OFFSET_CAPTURE
                )) {
                    foreach ($matches[0] as $hit) {
                        [$tag, $offset] = $hit;
                        if (!preg_match('/\\brel=(["\'])([^"\']*)\\1/', $tag, $rm)) {
                            $line = substr_count($src, "\n", 0, $offset) + 1;
                            $rel = str_replace(base_path() . '/', '', $file->getPathname());
                            $offenders[] = "{$rel}:{$line}  (no rel=)";
                            continue;
                        }
                        $tokens = preg_split('/\\s+/', $rm[2]);
                        if (!in_array('noopener', $tokens, true) || !in_array('noreferrer', $tokens, true)) {
                            $line = substr_count($src, "\n", 0, $offset) + 1;
                            $rel = str_replace(base_path() . '/', '', $file->getPathname());
                            $offenders[] = "{$rel}:{$line}  (rel='{$rm[2]}')";
                        }
                    }
                }
            }
        }

        $this->assertEmpty(
            $offenders,
            "AI-128 / SEC-03: every <a target=\"_blank\"> in Modules/ + src/ must carry rel=\"noopener noreferrer\". Offenders:\n  "
            . implode("\n  ", array_slice($offenders, 0, 30))
            . (count($offenders) > 30 ? "\n  ... (" . (count($offenders) - 30) . " more)" : '')
        );
    }
}
