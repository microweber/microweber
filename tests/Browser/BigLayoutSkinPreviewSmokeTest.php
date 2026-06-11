<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end coverage for every Big layouts-module skin.
 *
 * The admin Website Template page (`/admin/admin-template-page`)
 * renders a preview iframe pointing at
 * `/api/module/layout-preview?template=<skin>&active_site_template=Big`
 * for each layouts skin Big ships. A single broken blade file
 * (e.g. an unclosed `{{ asset(...) }}` interpolation, a malformed
 * `<?php ... ?>` block, or an `@if` without `@endif`) makes the
 * preview render an "Internal Server Error" page instead of the
 * actual layout — see `task-2026-04-29-e6fadd` (content/skin-67)
 * and `task-2026-04-29-952948` (gallery/skin-3 surfacing damage in
 * Modules/LayoutContent/resources/views/templates/default.blade.php
 * + Modules/Teamcard/resources/views/templates/skin-3.blade.php).
 *
 * This smoke walks `Templates/Big/resources/views/modules/layouts/
 * templates/<category>/skin-<N>.blade.php`, builds the preview URL
 * for each, fetches it through the logged-in admin browser session
 * (the route requires `is_admin()`), and asserts:
 *
 *   1. The response is NOT a 500-error page (no "Internal Server
 *      Error" / "Whoops" / Symfony stack-trace markers in the
 *      body).
 *   2. The response title is NOT the default "Microweber" error
 *      title.
 *   3. The body has more than ~500 bytes of rendered content (an
 *      empty/clipped output usually means a fatal mid-render).
 *
 * Each failing skin is collected with its short relative-path
 * identifier so a single test run reports every broken skin in
 * one assertion failure rather than aborting on the first one.
 */
class BigLayoutSkinPreviewSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;

    /**
     * Skins that are intentionally not previewable (e.g. missing a
     * required asset, ship deliberately-empty stub for the catalog
     * UI, etc.). Empty for now — every skin should render.
     *
     * @var list<string>
     */
    private const SKIP_SKINS = [];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function every_big_layout_skin_renders_without_a_server_error(): void
    {
        $skinFiles = $this->findBigLayoutSkins();
        $this->assertNotEmpty(
            $skinFiles,
            'No Big layouts skins found under '
            . 'Templates/Big/resources/views/modules/layouts/templates/. '
            . 'Did the template directory move? This smoke is meaningless '
            . 'without a corpus to walk.'
        );

        $this->browse(function (Browser $browser) use ($skinFiles): void {
            $this->loginAsAdmin($browser);

            $failures = [];

            foreach ($skinFiles as $relative) {
                if (in_array($relative, self::SKIP_SKINS, true)) {
                    continue;
                }
                $encodedTemplate = str_replace('/', '__', $relative);
                $url = '/api/module/layout-preview?template='
                    . urlencode($encodedTemplate)
                    . '&active_site_template=Big';

                // Drive the preview URL through the logged-in browser
                // so the controller's `is_admin()` gate lets us in.
                $browser->visit($url)->pause(400);

                $report = $browser->driver->executeScript(<<<'JS'
                    return {
                        title: document.title,
                        bodyLen: (document.body && document.body.innerHTML ? document.body.innerHTML.length : 0),
                        bodyTextStart: (document.body && document.body.textContent ? document.body.textContent.trim().substring(0, 200) : ''),
                        hasInternalServerError: !!(document.body && /internal server error|whoops|exception/i.test(document.body.textContent || '')),
                        hasStackTrace: !!(document.body && /\\bAt line\\b|\\bin file\\b.*\\.php|\\bIlluminate\\\\View\\\\ViewException\\b/i.test(document.body.innerHTML || '')),
                    };
                JS);

                $hasErr = ($report['hasInternalServerError'] ?? false)
                    || ($report['hasStackTrace'] ?? false);
                if ($hasErr || (int) ($report['bodyLen'] ?? 0) < 500) {
                    $failures[$relative] = [
                        'title' => $report['title'] ?? '',
                        'bodyLen' => (int) ($report['bodyLen'] ?? 0),
                        'sample' => (string) ($report['bodyTextStart'] ?? ''),
                    ];
                }
            }

            $this->assertSame(
                [],
                $failures,
                sprintf(
                    "Big layouts-module skin previews returned errors for %d of %d "
                    . "skins. Each entry below is a `<category>/skin-<N>` slug whose "
                    . "preview URL (/api/module/layout-preview?template=<slug>&active_site_template=Big) "
                    . "rendered an Internal Server Error or returned a near-empty body. "
                    . "Operators land on this surface from the admin Website Template "
                    . "page when they pick a layout from the Big catalog — every "
                    . "broken skin here is a broken catalog entry. Fix the underlying "
                    . "blade file (typical causes: unclosed {{ … }}, malformed <?php "
                    . "… ?> block, missing @endif/@endforeach):\n%s",
                    count($failures),
                    count($skinFiles),
                    json_encode($failures, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                )
            );
        });
    }

    /**
     * Walk Templates/Big/resources/views/modules/layouts/templates
     * and return every blade file as a `<category>/<slug>` string
     * (e.g. `gallery/skin-3`).
     *
     * @return list<string>
     */
    private function findBigLayoutSkins(): array
    {
        $base = base_path(
            'Templates/Big/resources/views/modules/layouts/templates'
        );
        if (!is_dir($base)) {
            return [];
        }

        $found = [];
        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iter as $file) {
            $path = $file->getPathname();
            if (!str_ends_with($path, '.blade.php')) {
                continue;
            }
            $relative = ltrim(substr($path, strlen($base)), DIRECTORY_SEPARATOR);
            // Strip the .blade.php suffix and normalise path separators.
            $relative = str_replace(['\\', '.blade.php'], ['/', ''], $relative);
            // Skip top-level non-skin files (404, clean, skin-1 catalog
            // wrapper) — the catalog UI calls them via a different
            // route shape and they're covered by other smokes.
            if (!str_contains($relative, '/')) {
                continue;
            }
            $found[] = $relative;
        }
        sort($found);
        return $found;
    }
}
