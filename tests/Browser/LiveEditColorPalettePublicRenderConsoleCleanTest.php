<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-3 color-scheme coverage (public-render console cleanliness).
 *
 * A palette JSON ships arbitrary strings as CSS var values. A malformed
 * entry (unclosed `calc()`, stray `;`, `var(--typo)` reference, etc.)
 * would still land on `:root` and the var-presence tests in
 * {@see LiveEditColorPalettePublicRenderTest} would stay green — the
 * var would just compute to its fallback. But the same malformed value
 * can still break client-side JS that reads `getComputedStyle` or tries
 * to parse the `--mw-*` custom property (e.g. the color-palette preview
 * thumbnail on the frontend, or any third-party theme/analytics script
 * that reads computed styles).
 *
 * This test closes that gap: for every shipping pack it applies the
 * palette, saves, drops the admin session, visits the public URL as
 * guest, and asserts **zero** uncaught errors and **zero** SEVERE
 * browser-log entries during a 3s settle window. One failing palette
 * fails one row, not the whole test.
 *
 * Noise filter mirrors
 * {@see LiveEditPublicPageConsoleCleanTest::CONSOLE_NOISE_PATTERNS}
 * so policy stays consistent with the sibling Phase-5 console check.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditColorPalettePublicRenderConsoleCleanTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    /**
     * @var array<int, string>
     */
    private const CONSOLE_NOISE_PATTERNS = [
        'install_log.txt',
        'ERR_CONTENT_LENGTH_MISMATCH',
    ];

    /**
     * @return iterable<string, array{0:string}>
     */
    public static function paletteProvider(): iterable
    {
        // Data providers run before the Laravel app boots, so resolve
        // the template dir relative to this file instead of via base_path().
        $root = dirname(__DIR__, 2);
        $dir = $root . '/Templates/Bootstrap/resources/assets/design-styles/style-packs/colors';

        $files = is_dir($dir) ? (glob($dir . '/*.json') ?: []) : [];
        sort($files);

        foreach ($files as $file) {
            $slug = pathinfo($file, PATHINFO_FILENAME);
            yield $slug => [$slug];
        }
    }

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    #[DataProvider('paletteProvider')]
    public function palette_public_render_has_clean_console(string $slug): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            $slug,
            $packs,
            "color pack '{$slug}' must be discoverable on disk"
        );
        $pack = $packs[$slug];

        $fixture = ColorPaletteFactory::make("public-render-console-{$slug}");

        $this->browse(function (Browser $browser) use ($fixture, $pack, $slug) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->clickPalette($browser, $slug);
            $this->assertPaletteApplied($browser, $pack['properties']);

            $this->saveLiveEdit($browser);
            $browser->pause(1500);

            // Drop the admin session so the public render executes with
            // guest JS only — admin toolbar scripts would otherwise mask
            // or trigger errors public visitors never encounter.
            $browser->driver->manage()->deleteAllCookies();
            \Tests\DuskTestCase::$adminLoggedIn = false;

            // Drain any prior-page log entries so the post-visit read is
            // scoped to the public visit only.
            $this->drainBrowserLog($browser);

            $browser->visit('/' . ltrim($fixture->slug, '/'));
            $this->installInPageErrorGuard($browser);

            // Settle window: initial parse, deferred scripts, then any
            // DOMContentLoaded / load bootstrap code (Swiper, fonts,
            // third-party analytics that reads computed styles).
            $browser->pause(3000);

            $inPageErrors = $this->readInPageErrors($browser);
            $severeLogs = $this->readSevereLogs($browser);

            $this->assertSame(
                [],
                $inPageErrors,
                "Public render of '{$fixture->slug}' under pack '{$slug}' must not "
                . "emit uncaught errors or unhandled promise rejections during "
                . "load + 3s settle window. Events: " . json_encode($inPageErrors)
            );

            $this->assertSame(
                [],
                $severeLogs,
                "Public render of '{$fixture->slug}' under pack '{$slug}' must not "
                . "produce SEVERE console entries (JS parse errors, top-level "
                . "throws, failed resource loads that promote to SEVERE). Entries: "
                . json_encode($severeLogs)
            );
        });
    }

    private function installInPageErrorGuard(Browser $browser): void
    {
        $browser->script("
            (function () {
                if (window.__paletteConsoleGuardInstalled) return;
                window.__paletteConsoleGuardInstalled = true;
                window.__paletteConsoleGuardEvents = [];

                window.addEventListener('error', function (ev) {
                    try {
                        window.__paletteConsoleGuardEvents.push({
                            kind: 'error',
                            message: (ev && ev.message) ? String(ev.message) : '',
                            filename: (ev && ev.filename) ? String(ev.filename) : '',
                            line: (ev && typeof ev.lineno === 'number') ? ev.lineno : null
                        });
                    } catch (e) {}
                });

                window.addEventListener('unhandledrejection', function (ev) {
                    try {
                        var reason = ev && ev.reason;
                        var msg = '';
                        if (reason) {
                            if (typeof reason === 'string') msg = reason;
                            else if (reason.message) msg = String(reason.message);
                            else { try { msg = JSON.stringify(reason); } catch (e) { msg = String(reason); } }
                        }
                        window.__paletteConsoleGuardEvents.push({
                            kind: 'unhandledrejection',
                            message: msg,
                            filename: '',
                            line: null
                        });
                    } catch (e) {}
                });
            })();
        ");
    }

    /**
     * @return array<int, array{kind:string, message:string, filename:string, line:int|null}>
     */
    private function readInPageErrors(Browser $browser): array
    {
        $res = $browser->script("
            var events = (window.__paletteConsoleGuardEvents || []).slice();
            return events;
        ");

        $payload = $res[0] ?? [];
        if (!is_array($payload)) return [];

        $out = [];
        foreach ($payload as $event) {
            if (!is_array($event)) continue;
            $message = (string)($event['message'] ?? '');
            if ($this->isNoise($message)) continue;
            $out[] = [
                'kind' => (string)($event['kind'] ?? ''),
                'message' => $message,
                'filename' => (string)($event['filename'] ?? ''),
                'line' => is_int($event['line'] ?? null) ? (int)$event['line'] : null,
            ];
        }
        return $out;
    }

    private function drainBrowserLog(Browser $browser): void
    {
        try {
            $browser->driver->manage()->getLog('browser');
        } catch (\Throwable) {
            // ChromeDriver log API can be flaky under certain builds
        }
    }

    /**
     * @return array<int, array{level:string, message:string}>
     */
    private function readSevereLogs(Browser $browser): array
    {
        try {
            $logs = $browser->driver->manage()->getLog('browser');
        } catch (\Throwable) {
            return [];
        }

        $out = [];
        foreach ($logs as $log) {
            $level = (string)($log['level'] ?? '');
            $message = (string)($log['message'] ?? '');
            if ($level !== 'SEVERE') continue;
            if ($this->isNoise($message)) continue;
            $out[] = ['level' => $level, 'message' => $message];
        }
        return $out;
    }

    private function isNoise(string $message): bool
    {
        foreach (self::CONSOLE_NOISE_PATTERNS as $pattern) {
            if ($pattern !== '' && strpos($message, $pattern) !== false) {
                return true;
            }
        }
        return false;
    }
}
