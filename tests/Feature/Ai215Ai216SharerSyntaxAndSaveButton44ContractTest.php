<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-167 / AI-215 + AI-216 (2026-05-10) — two unrelated bug fixes
 * shipped together because both surfaced from the AI-208 Live Edit
 * audit.
 *
 * AI-215 — Sharer module template syntax error
 * ============================================
 *
 * agent-test reported every post-detail page returning HTTP 500 in
 * the Live Edit iframe. Investigation: ALL post-detail pages 500
 * (not just in Live Edit). Root cause in the laravel.log:
 *
 *   syntax error, unexpected token "=", expecting ")"
 *   (View: Modules/Sharer/resources/views/templates/default.blade.php)
 *
 * Three lines (5, 52, 59) had a botched search-replace that
 * injected `rel="noopener noreferrer"` INSIDE the PHP expression
 * `mw()->url->current()`, producing the malformed
 * `mw()- rel="noopener noreferrer">url->current()` — `->` arrow
 * operator broken into `- ... >`.
 *
 * Fix: restore `mw()->url->current()` and move the rel attribute
 * onto the `<a>` tag where it belongs.
 *
 * AI-216 — Live Edit SAVE button below 44x44
 * ==========================================
 *
 * SaveButton.vue:109 renders `<button class="btn btn-dark live-edit-
 * toolbar-buttons" id="save-button">`. Bootstrap `.btn` defaults to
 * ~36px tall — agent-test measured 66x36. Same pattern as AI-181
 * (VIEW button): pin min-width/min-height: 44px in the existing
 * scoped `@media` block in `live-edit-mobile.css`.
 */
class Ai215Ai216SharerSyntaxAndSaveButton44ContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function ai_215_sharer_template_no_corrupted_arrow_chain(): void
    {
        $src = $this->read('Modules/Sharer/resources/views/templates/default.blade.php');

        // Strip blade comments before scanning so the documentation
        // about the bug doesn't trigger a false positive.
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $src);

        $this->assertDoesNotMatchRegularExpression(
            '/mw\(\)-\s+rel\s*=/',
            (string) $stripped,
            'Sharer/templates/default.blade.php MUST NOT carry the '
            . 'corrupted `mw()- rel="..."` chain — that broke the `->` '
            . 'operator and produced a blade-compile syntax error '
            . 'fatal-ing every post-detail page render.'
        );
        // Positive check: the canonical `mw()->url->current()` chain
        // MUST be present (at least one occurrence — facebook share).
        $this->assertMatchesRegularExpression(
            '/mw\(\)->url->current\(\)/',
            $src,
            'Sharer/templates/default.blade.php MUST use the canonical '
            . '`mw()->url->current()` chain to build share URLs.'
        );
    }

    #[Test]
    public function ai_215_sharer_template_carries_anchor(): void
    {
        $src = $this->read('Modules/Sharer/resources/views/templates/default.blade.php');
        $this->assertStringContainsString('AI-215', $src,
            'Sharer/templates/default.blade.php MUST carry the AI-215 '
            . 'anchor inline so the cycle-167 fix is discoverable.');
    }

    #[Test]
    public function ai_215_share_anchors_carry_rel_noopener(): void
    {
        $src = $this->read('Modules/Sharer/resources/views/templates/default.blade.php');
        // Cycle-167 moved `rel="noopener noreferrer"` onto the <a>
        // tag (where it belongs as an HTML attribute). The facebook,
        // whatsapp, and telegram share anchors MUST all carry it for
        // the security-best-practice reason that target=_blank links
        // need rel=noopener to prevent reverse-tabnabbing.
        $shareAnchorCount = preg_match_all(
            '/<a\s+target="_blank"\s+rel="noopener noreferrer"/',
            $src,
        );
        $this->assertGreaterThanOrEqual(3, $shareAnchorCount,
            'Sharer/templates/default.blade.php MUST have at least 3 '
            . '`<a target="_blank" rel="noopener noreferrer"` share '
            . "anchors (facebook + whatsapp + telegram). Found {$shareAnchorCount}.");
    }

    #[Test]
    public function ai_216_save_button_pinned_to_44_min(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertStringContainsString('AI-216', $src,
            'live-edit-mobile.css MUST carry the AI-216 anchor.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-167/', $src,
            'live-edit-mobile.css MUST carry the cycle-167 anchor.');

        // Both id selectors (admin-live-edit-page + live-edit-page
        // scopes) MUST hit min-width/min-height: 44px. Same pattern
        // as AI-181 (VIEW button) and AI-182 (user-menu hamburger).
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+#save-button[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST pin min-height:44px !important '
            . 'on .mw-admin-live-edit-page #save-button so the SAVE '
            . 'button meets the WCAG 2.5.5 / iOS HIG 44 floor (was 66x36).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+#save-button[\s\S]{0,400}min-width:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST pin min-width:44px !important on '
            . '.mw-admin-live-edit-page #save-button.'
        );
    }

    #[Test]
    public function ai_216_built_bundle_carries_save_button_floor(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson: load-bearing rule MUST
        // appear in the built bundle.
        $this->assertStringContainsString('#save-button', $built,
            'Built filament-theme bundle MUST contain the #save-button '
            . 'rule. If missing, the bundle was not rebuilt after the '
            . 'source edit.');
        $this->assertMatchesRegularExpression(
            '/#save-button[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $built,
            'Built bundle MUST contain min-height:44px !important on '
            . '#save-button.'
        );
    }
}
