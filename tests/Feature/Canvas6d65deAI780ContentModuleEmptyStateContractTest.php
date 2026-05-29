<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-6d65de / AI-780 — live-edit canvas posts module empty
 * state. Jira: https://microweber.atlassian.net/browse/AI-780
 *
 * Designer's Round-10 audit flagged the generic empty-state copy
 * "No content added. Please add content to the module." in the
 * Content module's default template as:
 *   - generic (doesn't identify which module the user is editing)
 *   - no CTA (offers no path forward)
 *   - passive (matches the AI-705 anti-pattern)
 *
 * Fix mirrors the AI-728/729/730 admin shipped pattern:
 *   - title + action-aware body + primary CTA
 *   - module-type aware via $params['content_type']:
 *       'post' → "No posts yet" / "+ Add post"
 *       'page' → "No pages yet" / "+ Add page"
 *       default → "No content yet" / "+ Add content"
 *   - CTA links to /admin/content/create?content_type=<type>
 *   - is_admin() gate preserved per AI-104 (cycle-101) — public users
 *     never see the placeholder
 *
 * Companion CSS lives in
 * packages/frontend-assets/resources/assets/css/microweber/css/default.css
 * (NOT general-styles.css) because the canvas iframe loads the
 * front-end template stack. Per AI-771 cross-package @import
 * architecture, default.css feeds both Vite bundle AND Webpack theme
 * bundle — one source, both pipelines.
 *
 * Token-scoping per SOUL #108 — every var() carries a literal fallback
 * because the iframe canvas runs outside the .mw-live-edit-page wrapper
 * AND outside admin chrome.
 */
class Canvas6d65deAI780ContentModuleEmptyStateContractTest extends TestCase
{
    private string $blade;
    private string $defaultCss;
    private string $bundleFrontend;
    private string $bundleTheme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/templates/default.blade.php'
        ));
        $this->defaultCss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/css/microweber/css/default.css'
        ));
        $this->bundleFrontend = file_exists(base_path(
            'public/vendor/microweber-packages/frontend-assets/build/default.css'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/frontend-assets/build/default.css'
        )) : '';
        $this->bundleTheme = file_exists(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Blade markup is type-aware + carries CTA
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function blade_renders_post_type_strings_and_cta(): void
    {
        $this->assertStringContainsString("__('No posts yet')", $this->blade);
        $this->assertStringContainsString("__('Add your first post to fill this module.')", $this->blade);
        $this->assertStringContainsString("__('+ Add post')", $this->blade);
        // task-2026-05-18-561d00 — admin_url caused 404 after Filament route reorganisation;
        // CTA now uses route('filament.admin.resources.posts.create').
        $this->assertStringContainsString("route('filament.admin.resources.posts.create')", $this->blade);
    }

    #[Test]
    public function blade_renders_page_type_strings_and_cta(): void
    {
        $this->assertStringContainsString("__('No pages yet')", $this->blade);
        $this->assertStringContainsString("__('Add your first page to fill this module.')", $this->blade);
        $this->assertStringContainsString("__('+ Add page')", $this->blade);
        // task-2026-05-18-561d00 — admin_url caused 404; CTA now uses route('filament.admin.resources.pages.create').
        $this->assertStringContainsString("route('filament.admin.resources.pages.create')", $this->blade);
    }

    #[Test]
    public function blade_has_fallback_type_strings_and_cta(): void
    {
        // When $params['content_type'] is null/unknown, fallback to a
        // generic "No content yet" with the bare admin/content/create URL.
        $this->assertStringContainsString("__('No content yet')", $this->blade);
        $this->assertStringContainsString("__('Add your first item to fill this module.')", $this->blade);
        $this->assertStringContainsString("__('+ Add content')", $this->blade);
        // task-2026-05-18-561d00 — admin_url caused 404; CTA now uses route('filament.admin.resources.contents.create').
        $this->assertStringContainsString("route('filament.admin.resources.contents.create')", $this->blade);
    }

    #[Test]
    public function blade_preserves_is_admin_gate_from_ai104(): void
    {
        // AI-104 (cycle-101 2026-05-09) wrapped the empty-state
        // placeholder in is_admin() so anonymous public visitors
        // don't see the editor-facing hint. Must remain after AI-780.
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*empty\s*\(\s*\$data\s*\)\s*\)[\s\S]*?@if\s*\(\s*is_admin\s*\(\s*\)\s*\)[\s\S]*?mw-canvas-empty-state/s',
            $this->blade,
            'AI-780 empty-state markup must remain wrapped inside the AI-104 `@if(is_admin())` gate.'
        );
    }

    #[Test]
    public function blade_carries_module_type_data_attribute_and_aria_label(): void
    {
        // data-mw-ai780-content-type attribute identifies which copy
        // path fired (debuggability + future analytics hook). aria-label
        // on the CTA ensures the action text is the announced label
        // (defensive for AT users — visible text already matches).
        $this->assertMatchesRegularExpression(
            '/data-mw-ai780-content-type="\{\{[^}]*\$mwAi780Type/',
            $this->blade,
            'AI-780 wrapper div must carry `data-mw-ai780-content-type` for debuggability.'
        );
        $this->assertMatchesRegularExpression(
            '/aria-label="\{\{[^}]*\$mwAi780CtaLabel/',
            $this->blade,
            'AI-780 CTA must carry aria-label bound to the same label string.'
        );
    }

    #[Test]
    public function legacy_generic_copy_is_gone_from_default_template(): void
    {
        // Strip Blade {{-- … --}} comments first (selector-self-match
        // guard family — the AI-104 comment and possibly other prose
        // may reference the old copy).
        $stripped = preg_replace('/\{\{--.*?--\}\}/s', '', $this->blade);
        $this->assertStringNotContainsString(
            'No content added. Please add content to the module.',
            $stripped,
            'Legacy generic "No content added. Please add content to the module." copy must be gone from the rendered template (only the new AI-780 type-aware empty state remains).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — CSS chrome lives in default.css with token fallbacks
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function default_css_carries_mw_canvas_empty_state_rules(): void
    {
        $this->assertStringContainsString('.mw-canvas-empty-state {', $this->defaultCss);
        $this->assertStringContainsString('.mw-canvas-empty-state__title', $this->defaultCss);
        $this->assertStringContainsString('.mw-canvas-empty-state__body', $this->defaultCss);
        $this->assertStringContainsString('.mw-canvas-empty-state__cta', $this->defaultCss);
        $this->assertStringContainsString('html.dark .mw-canvas-empty-state', $this->defaultCss);
    }

    #[Test]
    public function default_css_cta_meets_wcag_44px_touch_target(): void
    {
        // The CTA is an interactive anchor — must hit the WCAG 2.5.5
        // 44×44 floor per the project-wide touch-target policy.
        $start = strpos($this->defaultCss, '.mw-canvas-empty-state__cta {');
        $this->assertNotFalse($start);
        $end = strpos($this->defaultCss, '}', $start);
        $slice = substr($this->defaultCss, $start, $end - $start);
        $this->assertMatchesRegularExpression('/min-height:\s*44px/', $slice);
    }

    #[Test]
    public function default_css_token_fallbacks_present_on_every_var_in_slice(): void
    {
        // SOUL #108 — every var() in the AI-780 slice must carry a
        // literal fallback. Slice from AI-780 docblock end to next AI
        // marker / EOF (selector-self-match guard family).
        $start = strpos($this->defaultCss, 'AI-780 (task-2026-05-17-6d65de)');
        $this->assertNotFalse($start);
        $docEnd = strpos($this->defaultCss, '*/', $start);
        $this->assertNotFalse($docEnd);
        $sliceStart = $docEnd + 2;
        $sliceEnd = strpos($this->defaultCss, "/*", $sliceStart);
        $slice = $sliceEnd === false
            ? substr($this->defaultCss, $sliceStart)
            : substr($this->defaultCss, $sliceStart, $sliceEnd - $sliceStart);
        preg_match_all('/var\(([^)]+)\)/', $slice, $matches);
        foreach ($matches[1] as $varExpr) {
            $this->assertStringContainsString(
                ',',
                $varExpr,
                "Every var() in the AI-780 CSS slice must carry a literal fallback. Offender: `var({$varExpr})`."
            );
        }
        $this->assertGreaterThan(0, count($matches[1]), 'AI-780 slice must consume ESE tokens.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — bundle runtime probe (both pipelines per AI-771 architecture)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_frontend_default_carries_rule(): void
    {
        if ($this->bundleFrontend === '') {
            $this->markTestSkipped('Served frontend-assets/build/default.css absent — run `cd packages/frontend-assets && npm run build`.');
        }
        $this->assertStringContainsString(
            '.mw-canvas-empty-state',
            $this->bundleFrontend,
            'Served frontend-assets default.css must carry the AI-780 rule.'
        );
    }

    #[Test]
    public function bundle_theme_carries_rule_via_cross_import(): void
    {
        if ($this->bundleTheme === '') {
            $this->markTestSkipped('Served microweber-filament-theme.css absent — run `cd packages/microweber-filament-theme && npm run build`.');
        }
        // The theme bundle gets the rule via the cross-package @import
        // (per AI-771). If this assertion fails AFTER the frontend-assets
        // bundle has the rule, the theme bundle is stale (rebuild) OR
        // the @import line in index.css:34 was removed (architectural).
        $this->assertStringContainsString(
            '.mw-canvas-empty-state',
            $this->bundleTheme,
            'Served theme bundle must carry the AI-780 rule via cross-package @import. If absent, run `cd packages/microweber-filament-theme && npm run build`.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + general-styles.css pointer comment preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai780_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-6d65de', $this->blade);
        $this->assertStringContainsString('AI-780', $this->blade);
        $this->assertStringContainsString('task-2026-05-17-6d65de', $this->defaultCss);
        $this->assertStringContainsString('AI-780', $this->defaultCss);
    }

    #[Test]
    public function general_styles_carries_pointer_comment_to_default_css(): void
    {
        // The relocation (general-styles.css → default.css) leaves a
        // pointer comment in general-styles.css so future audits can
        // grep `AI-780` in either file and find the rule.
        $generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->assertStringContainsString('AI-780 (task-2026-05-17-6d65de)', $generalStyles);
        $this->assertStringContainsString('default.css', $generalStyles);
    }
}
