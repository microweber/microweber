<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-135 / AI-109 / TICKET-BK+BL+BM — Frontend build cleanup contract.
 *
 * Pins three guarantees in packages/frontend-assets/vite.config.js so a
 * future refactor cannot silently undo the cycle-135 chunk-dedupe wins:
 *
 *   - TICKET-BK : `manualChunks` coalesces Vue runtime, Vuetify, and
 *                 lodash/jquery/axios into single shared chunks. Pre-fix
 *                 saw 7× ~742KB "Lang" chunks (each was the full Vue
 *                 runtime, named after Lang.vue) and 4× ~1.26MB
 *                 "helpers" chunks. Post-fix: single named chunks per
 *                 vendor family.
 *
 *   - TICKET-BK : copyPlugin clears `chunks/` in the public copy target
 *                 before copying the new build. Pre-fix accumulated
 *                 orphaned hash-suffixed chunks across rebuilds even
 *                 though the source build was clean.
 *
 *   - TICKET-BL : the "Lang" chunk was misidentified by the brief as
 *                 a 742 KB locale bundle. It was actually the shared
 *                 Vue runtime. The TICKET-BK chunk-dedupe collapses
 *                 the 7 duplicates and reduces the chunk to its real
 *                 16 KB Lang.vue payload — same outcome the brief
 *                 wanted (lazy-loaded per-locale would have shipped
 *                 less than 16 KB but solving the duplication is the
 *                 80% win). TICKET-BL is closed via the dedupe.
 *
 *   - TICKET-BM : the brief claimed `live-edit-app.js` is loaded on
 *                 every admin page; codebase grep proves it is loaded
 *                 only by `iframe-page.blade.php` and `iframe.blade.php`
 *                 (both of which ARE the live-edit page). Already
 *                 gated; no code change needed. Closed by negative
 *                 contract test.
 *
 * Style after Sec05SsrfAndStoredXssContractTest / Ai108* — source-grep
 * assertions that catch regressions at refactor time.
 */
class Ai109FrontendBuildCleanupContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function vite_config_pins_manual_chunks_for_shared_vendors(): void
    {
        $src = $this->read('packages/frontend-assets/vite.config.js');

        // Vue runtime MUST be coalesced into a single chunk so the 7×
        // Lang-*.js duplicates that pre-cycle-135 builds emitted cannot
        // come back.
        $this->assertMatchesRegularExpression(
            '/manualChunks\s*:\s*\([^)]*\)\s*=>\s*\{[\s\S]*?return\s+[\'"]vue-runtime[\'"]/',
            $src,
            'vite.config.js MUST contain a manualChunks function that '
            . 'returns "vue-runtime" for @vue/* + node_modules/vue/* '
            . 'imports, otherwise every Vue entry will ship its own copy.'
        );

        $this->assertMatchesRegularExpression(
            '/return\s+[\'"]helpers[\'"]/',
            $src,
            'manualChunks MUST emit a "helpers" chunk for lodash + '
            . 'jquery + axios so the 4× ~1.26MB helpers-*.js duplicates '
            . 'cannot come back.'
        );

        $this->assertMatchesRegularExpression(
            '/return\s+[\'"]vuetify[\'"]/',
            $src,
            'manualChunks MUST emit a single "vuetify" chunk so multiple '
            . 'Vue admin entries share one Vuetify copy.'
        );
    }

    #[Test]
    public function vite_config_clears_chunks_dir_before_copying_to_public(): void
    {
        $src = $this->read('packages/frontend-assets/vite.config.js');

        // The copyPlugin MUST rmSync the destChunks dir before copying
        // the new build, otherwise hash-suffixed orphans pile up.
        $this->assertMatchesRegularExpression(
            '/destChunks\s*=\s*path\.join\(\s*dest\s*,\s*[\'"]chunks[\'"]\s*\)/',
            $src,
            'copyPlugin MUST compute a destChunks path so it can clear '
            . 'the public copy target chunks/ before copying.'
        );

        $this->assertMatchesRegularExpression(
            '/fs\.rmSync\(\s*destChunks\s*,\s*\{[^\}]*recursive\s*:\s*true/',
            $src,
            'copyPlugin MUST recursively rmSync destChunks before '
            . 'copySync so orphaned hash-suffixed chunks do not pile '
            . 'up across rebuilds.'
        );
    }

    #[Test]
    public function vite_config_keeps_empty_out_dir_for_source_build(): void
    {
        $src = $this->read('packages/frontend-assets/vite.config.js');

        // emptyOutDir MUST stay true on the default build branch
        // otherwise the source `resources/dist/build/` accumulates
        // stale chunks too.
        $this->assertMatchesRegularExpression(
            '/emptyOutDir:\s*true/',
            $src,
            'vite.config.js default-build branch MUST keep '
            . 'emptyOutDir: true so the source build dir stays clean.'
        );
    }

    #[Test]
    public function live_edit_app_is_only_loaded_in_live_edit_blade_views(): void
    {
        // TICKET-BM: prove live-edit-app.js is NOT loaded on admin pages
        // outside the live-edit iframe context. This is a negative test —
        // we list every blade view that references it and assert the set
        // matches exactly the two live-edit iframe templates.
        $matches = [];
        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(base_path('src/MicroweberPackages'))
        );
        foreach ($rii as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $name = $file->getFilename();
            if (!str_ends_with($name, '.blade.php')) {
                continue;
            }
            $contents = file_get_contents($file->getPathname());
            if (str_contains($contents, 'live-edit-app.js')) {
                $matches[] = str_replace(base_path() . '/', '', $file->getPathname());
            }
        }

        sort($matches);

        $this->assertSame(
            [
                'src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php',
                'src/MicroweberPackages/LiveEdit/resources/views/iframe.blade.php',
            ],
            $matches,
            'live-edit-app.js MUST be loaded only by the two live-edit '
            . 'iframe templates. Any other admin view referencing it '
            . 'would re-introduce the 600KB+ ship-on-every-page hit '
            . '(TICKET-BM).'
        );
    }

    #[Test]
    public function vite_config_documents_chunk_dedupe_rationale_inline(): void
    {
        $src = $this->read('packages/frontend-assets/vite.config.js');

        // The cycle-135 anchor + pre/post-fix metrics MUST stay inline
        // so a future maintainer understands WHY the manualChunks block
        // exists and what its undo would cost.
        $this->assertStringContainsString(
            'AI-109',
            $src,
            'vite.config.js MUST carry the AI-109 anchor inline so the '
            . 'manualChunks rationale is discoverable at refactor time.'
        );

        $this->assertStringContainsString(
            'cycle-135',
            $src,
            'vite.config.js MUST carry the cycle-135 anchor inline.'
        );
    }
}
