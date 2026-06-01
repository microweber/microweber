<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-01-ai1206 — AI-1206: mw.autoComplete dynamic-input accessible-name gap.
 *
 * Pre-fix state probed via Playwright at /admin/live-edit:
 *   The Live Edit toolbar "Search content" input (and every other mw.autoComplete consumer)
 *   rendered an <input> element created imperatively via DOM in the factory's createField()
 *   method. The dynamic input carried NO aria-label / NO title / NO associated <label> —
 *   AT users heard only "edit, blank". Markup-level fixes are impossible because consumers
 *   never see the input element directly; the only fix path is inside the factory.
 *   WCAG 3.3.2 Level A regression.
 *
 * Fix — TWO-LAYER (DUAL-SOURCE BUNDLE-SHADOW DEFENCE):
 *   The mw.autoComplete factory ships in TWO independent build pipelines that BOTH define
 *   `mw.autoComplete` on the global namespace. The legacy non-bundled
 *   `frontend-assets-libs/api/autocomplete.js` loads via a separate <script src> tag AFTER
 *   the bundled `admin.js` — silently OVERRIDING the patched factory unless both sources
 *   carry the fix. Patching one without the other produces a Tier-3-failing no-op.
 *
 *   Both sources MUST carry the identical AI-1196-style fallback chain inside createField():
 *     options.ariaLabel || options.placeholder || options.name || 'Search'
 *   bound to BOTH aria-label AND title on the input element.
 *
 * Consumer wiring (ContentSearchNav.vue): passes `ariaLabel: "Search content"` so the
 * "Search content" Live Edit consumer surfaces a descriptive accessible name rather than
 * the example-shaped placeholder.
 *
 * Sister-fix lineage:
 *   - AI-1196 (AIChatForm shared-component ariaLabel option, same fallback chain) — designer
 *     LESSONS entry pinned at the "shared component aria-label fallback chain" rule.
 *   - AI-817 (slider sweep), AI-1204 (mw-icon-picker clear-button), AI-1205 (ESE BorderRadius)
 *     — same family of WCAG 3.3.2 Level A regressions.
 *
 * NEW STAGE-FAMILY CANDIDATE — "bundle-shadow / dual-source-of-truth":
 *   When a module ships in TWO separate build pipelines that both serve into the same
 *   global namespace, fixing one is insufficient. Diagnostic recipe: Tier-3 probe failure
 *   despite bundle-grep success → `mw.<Symbol>.toString()` inspection → if factory body
 *   lacks fix marker → enumerate `document.querySelectorAll('script[src]')` URLs serving
 *   the patched symbol → locate parallel sources → patch all.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip JS `/* * /` block
 * comments + `//` line comments + Blade `{{-- --}}` comments before negative regression
 * assertions so docblock prose describing the pre-fix "no aria-label" pattern cannot
 * false-fail.
 */
class AutocompleteAI1206SearchInputAriaLabelContractTest extends TestCase
{
    private const BUNDLED_SOURCE_PATH = 'packages/frontend-assets/resources/assets/components/autocomplete.js';
    private const LEGACY_SOURCE_PATH = 'packages/frontend-assets-libs/resources/local-libs/api/autocomplete.js';
    private const SERVED_LEGACY_PATH = 'public/vendor/microweber-packages/frontend-assets-libs/api/autocomplete.js';
    private const CONSUMER_PATH = 'packages/frontend-assets/resources/assets/ui/components/Toolbar/ContentSearchNav.vue';

    private string $bundledSource;
    private string $legacySource;
    private string $servedLegacy;
    private string $consumerSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bundledSource = (string) file_get_contents(base_path(self::BUNDLED_SOURCE_PATH));
        $this->legacySource = (string) file_get_contents(base_path(self::LEGACY_SOURCE_PATH));
        $this->servedLegacy = (string) file_get_contents(base_path(self::SERVED_LEGACY_PATH));
        $this->consumerSource = (string) file_get_contents(base_path(self::CONSUMER_PATH));
    }

    private function stripJsComments(string $source): string
    {
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        return $stripped;
    }

    private function stripVueComments(string $source): string
    {
        $stripped = (string) preg_replace('~<!--[\s\S]*?-->~', '', $source);
        $stripped = $this->stripJsComments($stripped);
        return $stripped;
    }

    #[Test]
    public function all_autocomplete_source_files_exist(): void
    {
        $this->assertFileExists(base_path(self::BUNDLED_SOURCE_PATH), 'Bundled autocomplete.js source MUST exist');
        $this->assertFileExists(base_path(self::LEGACY_SOURCE_PATH), 'Legacy local-libs autocomplete.js MUST exist (frontend-assets-libs pipeline)');
        $this->assertFileExists(base_path(self::SERVED_LEGACY_PATH), 'Served legacy autocomplete.js MUST exist in public/ for runtime serving');
        $this->assertFileExists(base_path(self::CONSUMER_PATH), 'ContentSearchNav.vue consumer MUST exist');
    }

    #[Test]
    public function bundled_source_declares_fallback_chain_variable(): void
    {
        $this->assertMatchesRegularExpression(
            '/var\s+ai1206AccessibleName\s*=\s*this\.options\.ariaLabel\s*\|\|\s*this\.options\.placeholder\s*\|\|\s*this\.options\.name\s*\|\|\s*[\'"]Search[\'"]/',
            $this->bundledSource,
            'Bundled autocomplete.js MUST declare the AI-1206 fallback chain: ariaLabel || placeholder || name || "Search"'
        );
    }

    #[Test]
    public function bundled_source_sets_aria_label_and_title_on_input(): void
    {
        $this->assertStringContainsString(
            "this.inputField.setAttribute('aria-label', ai1206AccessibleName);",
            $this->bundledSource,
            'Bundled autocomplete.js MUST setAttribute("aria-label", ai1206AccessibleName) on the dynamic input'
        );

        $this->assertStringContainsString(
            "this.inputField.setAttribute('title', ai1206AccessibleName);",
            $this->bundledSource,
            'Bundled autocomplete.js MUST setAttribute("title", ai1206AccessibleName) — defence-in-depth tooltip'
        );
    }

    #[Test]
    public function legacy_source_declares_fallback_chain_variable(): void
    {
        $this->assertMatchesRegularExpression(
            '/var\s+ai1206AccessibleName\s*=\s*this\.options\.ariaLabel\s*\|\|\s*this\.options\.placeholder\s*\|\|\s*this\.options\.name\s*\|\|\s*[\'"]Search[\'"]/',
            $this->legacySource,
            'Legacy frontend-assets-libs/local-libs autocomplete.js MUST declare the SAME AI-1206 fallback chain — bundle-shadow defence'
        );
    }

    #[Test]
    public function legacy_source_sets_aria_label_and_title_on_input(): void
    {
        $this->assertStringContainsString(
            "this.inputField.setAttribute('aria-label', ai1206AccessibleName);",
            $this->legacySource,
            'Legacy autocomplete.js MUST setAttribute("aria-label", ...) — without this the legacy <script src> overrides the bundled fix at runtime'
        );

        $this->assertStringContainsString(
            "this.inputField.setAttribute('title', ai1206AccessibleName);",
            $this->legacySource,
            'Legacy autocomplete.js MUST setAttribute("title", ...) — defence-in-depth tooltip'
        );
    }

    #[Test]
    public function served_public_copy_carries_fix(): void
    {
        $this->assertStringContainsString(
            'ai1206AccessibleName',
            $this->servedLegacy,
            'Served public/vendor/.../autocomplete.js MUST carry the ai1206AccessibleName variable — local-libs change must be propagated to public/ for runtime serving'
        );

        $this->assertStringContainsString(
            "setAttribute('aria-label', ai1206AccessibleName)",
            $this->servedLegacy,
            'Served public copy MUST carry the setAttribute("aria-label", ...) call — confirms build pipeline propagation'
        );
    }

    #[Test]
    public function fallback_chain_priority_order_correct(): void
    {
        $pattern = '/this\.options\.ariaLabel\s*\|\|\s*this\.options\.placeholder\s*\|\|\s*this\.options\.name/';

        $this->assertMatchesRegularExpression(
            $pattern,
            $this->bundledSource,
            'Bundled source: ariaLabel MUST come BEFORE placeholder (consumer-supplied descriptive name wins over example placeholder, per AI-1196 LESSONS rule)'
        );

        $this->assertMatchesRegularExpression(
            $pattern,
            $this->legacySource,
            'Legacy source: ariaLabel MUST come BEFORE placeholder — both sources MUST share identical priority order'
        );
    }

    #[Test]
    public function consumer_content_search_nav_passes_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/ariaLabel:\s*[\'"]Search content[\'"]/',
            $this->consumerSource,
            'ContentSearchNav.vue MUST pass ariaLabel: "Search content" to mw.autoComplete so the Live Edit toolbar search input announces a descriptive name'
        );
    }

    #[Test]
    public function consumer_carries_ai1206_marker(): void
    {
        $this->assertStringContainsString(
            'AI-1206',
            $this->consumerSource,
            'ContentSearchNav.vue MUST carry the AI-1206 marker so the consumer-wiring decision is discoverable from grep'
        );
    }

    #[Test]
    public function task_marker_present_in_both_sources(): void
    {
        $this->assertStringContainsString(
            'task-2026-06-01-ai1206',
            $this->bundledSource,
            'Bundled source MUST carry task-2026-06-01-ai1206 marker'
        );

        $this->assertStringContainsString(
            'task-2026-06-01-ai1206',
            $this->legacySource,
            'Legacy source MUST carry task-2026-06-01-ai1206 marker — confirms dual-source fix was applied intentionally, not accidentally'
        );
    }

    #[Test]
    public function no_empty_aria_label_regression(): void
    {
        $bundledStripped = $this->stripJsComments($this->bundledSource);
        $legacyStripped = $this->stripJsComments($this->legacySource);
        $consumerStripped = $this->stripVueComments($this->consumerSource);

        $this->assertDoesNotMatchRegularExpression(
            '/setAttribute\(\s*[\'"]aria-label[\'"]\s*,\s*[\'"]{2}\s*\)/',
            $bundledStripped,
            'Bundled source MUST NOT setAttribute("aria-label", "") — empty accessible name is equivalent to no aria-label for WCAG 3.3.2'
        );

        $this->assertDoesNotMatchRegularExpression(
            '/setAttribute\(\s*[\'"]aria-label[\'"]\s*,\s*[\'"]{2}\s*\)/',
            $legacyStripped,
            'Legacy source MUST NOT setAttribute("aria-label", "")'
        );

        $this->assertDoesNotMatchRegularExpression(
            '/ariaLabel:\s*[\'"]{2}/',
            $consumerStripped,
            'Consumer MUST NOT pass ariaLabel: "" — empty consumer-supplied label collapses the entire fallback chain'
        );
    }

    #[Test]
    public function both_sources_share_byte_identical_fix_block(): void
    {
        $pattern = '/var\s+ai1206AccessibleName\s*=[\s\S]*?setAttribute\([\'"]title[\'"],\s*ai1206AccessibleName\);/';

        preg_match($pattern, $this->bundledSource, $bundledMatch);
        preg_match($pattern, $this->legacySource, $legacyMatch);

        $this->assertNotEmpty($bundledMatch, 'Bundled source MUST contain the full fix block (var declaration through title setAttribute)');
        $this->assertNotEmpty($legacyMatch, 'Legacy source MUST contain the full fix block');

        $this->assertSame(
            $bundledMatch[0],
            $legacyMatch[0],
            'Bundled + legacy fix blocks MUST be byte-identical — dual-source bundle-shadow defence requires identical factory behaviour regardless of which copy wins the runtime override race'
        );
    }
}
