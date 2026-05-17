<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-f1e4d9 / AI-854 — mw.fileWindow strict-mode break.
 * Jira: https://microweber.atlassian.net/browse/AI-854
 *
 * Pre-fix: packages/frontend-assets/resources/assets/tools/common-extend.js
 * line 53 assigned to `url` with NO var/let/const declaration. Under
 * strict-mode bundling (default for modern build pipelines), implicit-
 * global assignments throw `ReferenceError: url is not defined`.
 *
 * Production impact: packages/frontend-assets/resources/assets/components
 * /components.js:98 (the `'file-uploader'` component handler) actively
 * calls mw.fileWindow(). Every admin click on a file-uploader-bound
 * affordance silently errored -- no modal opened, no console error
 * visible to non-DevTools users. P1 because the legacy file-uploader
 * hook fan-out covers admin upload affordances across multiple surfaces.
 *
 * Fix: 1-character (logical) addition -- `var url = ...` declares the
 * local properly. Browser-natively scoped to the mw.fileWindow function
 * body; identical runtime semantics to the implicit-global version
 * under non-strict mode (which is what masked the defect in legacy
 * inline-script tests).
 *
 * Slice B (linting follow-up flagged in source comment + AI-854b): run
 * `npx eslint packages/frontend-assets/resources/assets/ --rule
 * 'no-undef: error'` to surface sibling implicit-global assignments in
 * the legacy tools/ directory.
 */
class CommonExtendF1e4d9AI854FileWindowVarContractTest extends TestCase
{
    private const COMMON_EXTEND = 'packages/frontend-assets/resources/assets/tools/common-extend.js';

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — positive guard: var url = ...
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function var_url_declaration_present_on_rte_image_editor_assign(): void
    {
        $source = $this->read(self::COMMON_EXTEND);

        $this->assertMatchesRegularExpression(
            "/var\\s+url\\s*=\\s*mw\\.settings\\.site_url\\s*\\+\\s*['\"]editor_tools\\/rte_image_editor\\?['\"]\\s*\\+\\s*\\\$\\.param\\(q\\)\\s*\\+\\s*['\"]#fileWindow['\"]\\s*;/",
            $source,
            'AI-854: mw.fileWindow MUST declare `var url = ...` before assigning. Pre-fix shape was implicit-global assignment that throws ReferenceError under strict-mode bundling.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — negative regression guard: no implicit-global `url = ...`
    // for the rte_image_editor URL build
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function legacy_implicit_global_url_assign_gone(): void
    {
        $source = $this->read(self::COMMON_EXTEND);

        // Selector-self-match guard (18+ session-recurrences). Strip JS
        // comments + Blade comments before negative assert so docblock
        // prose mentioning the legacy shape doesn't false-fail.
        $stripped = preg_replace('~//[^\n]*~', '', $source);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);

        // Pre-fix shape: `url = ` NOT preceded by any of var/let/const
        // (i.e. bare identifier assignment at statement scope). Look
        // for the specific rte_image_editor build expression so we
        // don't false-fail other legitimate `url = ` assignments.
        $this->assertDoesNotMatchRegularExpression(
            "/(?<![\\w\\.\\$])url\\s*=\\s*mw\\.settings\\.site_url\\s*\\+\\s*['\"]editor_tools\\/rte_image_editor/",
            preg_replace('/var\s+url\s*=/', 'var XXX_DECLARED =', $stripped),
            'AI-854 regression guard: no implicit-global `url = mw.settings.site_url + ...rte_image_editor...` assignment must remain. The declared `var url = ...` form is masked first; any remaining bare-identifier assignment is the legacy defect.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — preserved surrounding code (no regression in mw.fileWindow
    // body)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function mw_filewindow_function_body_preserved(): void
    {
        $source = $this->read(self::COMMON_EXTEND);

        // mw.fileWindow function signature + key body landmarks must
        // remain intact (defect was scoped to ONE line; everything
        // else stays).
        $this->assertStringContainsString(
            'mw.fileWindow = function (config) {',
            $source,
            'AI-854: mw.fileWindow assignment must be preserved verbatim.'
        );
        $this->assertStringContainsString(
            "config.mode = config.mode || 'dialog'",
            $source,
            'AI-854: mw.fileWindow body must continue to default config.mode = "dialog".'
        );
        $this->assertStringContainsString(
            'mw.lang(\'Select image\')',
            $source,
            'AI-854: mw.fileWindow dialog title binding must be preserved.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — task-id markers (audit grep contract)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present(): void
    {
        $source = $this->read(self::COMMON_EXTEND);

        $this->assertStringContainsString(
            'task-2026-05-17-f1e4d9',
            $source,
            'AI-854: common-extend.js MUST carry the AI-854 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-854',
            $source,
            'AI-854: common-extend.js MUST cite the AI-854 ticket ID.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — served-bundle runtime probe (the AI-bc28fd 3-stage chain:
    //          source → served → loaded; this group covers stage 2)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function served_bundle_carries_var_url_declaration_if_built(): void
    {
        $bundle = base_path('public/vendor/microweber-packages/frontend-assets/build/admin.js');
        if (! file_exists($bundle)) {
            $this->markTestSkipped('Built admin.js bundle not present; runtime probe skipped on fresh-clone CI.');
        }

        $content = (string) file_get_contents($bundle);

        // The built bundle minifier may rename `url` but the declaration
        // (var/let/const) MUST be present immediately before the
        // rte_image_editor URL build. Look for a declaration keyword
        // within ~80 chars before the editor_tools/rte_image_editor
        // string literal.
        $pos = strpos($content, 'editor_tools/rte_image_editor');
        if ($pos === false) {
            $this->markTestSkipped('rte_image_editor literal not found in served bundle (minified out?); runtime probe skipped.');
        }

        // Minifier may collapse the declaration into a comma-list with
        // earlier locals: `var t={...},i=mw.settings.site_url+...`. The
        // ~200-char window safely covers the function-body opener
        // through the rte_image_editor literal in the minified shape.
        $window = substr($content, max(0, $pos - 200), 200);
        $this->assertMatchesRegularExpression(
            '/\b(?:var|let|const)\s+\w+\s*=/',
            $window,
            "AI-854 runtime probe: served admin.js bundle MUST carry a var/let/const declaration within 200 chars before the rte_image_editor URL literal. Pre-fix bundle shipped an implicit-global assignment that throws ReferenceError under strict-mode."
        );
    }
}
