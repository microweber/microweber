<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-860f75 / AI-691a — §A6 Path B follow-up:
 * hide the Add-Content picker modal Cancel button on desktop
 * (≥769px) while keeping it on mobile (≤768px) for NOVICE #3
 * thumb-reach affordance.
 *
 * Designer resolution shape (Path B, accepted under design
 * authority — see email 2026-05-16T12:53):
 *
 *   - Desktop ≥769px: hide Cancel via a single scoped CSS rule.
 *   - Mobile ≤768px: Cancel stays (no rule applies) until AI-695
 *     bottom-sheet ships and the whole Cancel DOM gets deleted.
 *   - Scoping: target `.mw-content-picker-modal` only, never the
 *     global `.fi-modal-footer-actions` — per the
 *     `live-edit-css-must-be-scoped` skill.
 *
 * The implementation lands as an additive @media block at the
 * end of the existing admin-side picker-styles area in
 * `live-edit-module-settings.blade.php`, next to the other
 * `.mw-content-picker-modal` rules. modalSubmitAction(false) is
 * already set on `addContentAction()` so the footer contains only
 * the Cancel button — hiding the wrapper is equivalent to hiding
 * the button.
 *
 * The companion PHP-side `->modalCancelActionLabel('Cancel')`
 * call stays exactly where AI-691 (task-cdeefd) left it; mobile
 * relies on it. AI-691's
 * `cancel_button_kept_pending_designer_a6_resolution` test
 * continues to pass because Path B preserves the PHP line
 * verbatim — the change is CSS-only.
 */
class AddContent860f75AI691aCancelDesktopHideContractTest extends TestCase
{
    private string $blade;
    private string $page;
    private string $mobileCss;

    protected function setUp(): void
    {
        parent::setUp();
        // task-bc28fd CHANGE (designer per SOUL #108 verify-before-
        // accept): the AI-691a rule moved OUT of the
        // live-edit-module-settings.blade.php `<style>` block (which
        // only loaded on the module-settings sub-form page, NOT the
        // live-edit canvas) INTO live-edit-classes.css (Webpack-
        // bundled, loaded on /admin/live-edit). $blade now points
        // at the new host file so existing assertions continue to
        // verify presence at the correct location.
        $this->blade = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
        $this->page = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php'
        ));
        $this->mobileCss = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css'
        ));
    }

    #[Test]
    public function rule_is_inside_a_min_width_769px_media_block(): void
    {
        // The rule MUST be a desktop-only @media block. Mobile (≤768px)
        // must not match — that preserves Cancel for the NOVICE #3
        // thumb-reach affordance until AI-695 lands.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*min-width:\s*769px\s*\)\s*\{[^}]*\.mw-content-picker-modal\s+\.fi-modal-footer-actions\s*\{[^}]*display:\s*none/s',
            $this->blade,
            'AI-691a rule must be inside @media (min-width: 769px) and scoped to .mw-content-picker-modal .fi-modal-footer-actions.'
        );
    }

    #[Test]
    public function rule_is_scoped_not_global(): void
    {
        // Guard: the rule body must NOT contain a bare
        // `.fi-modal-footer-actions { display: none }` declaration —
        // that would hit every Filament modal across the admin panel.
        // Slice out the rule body (everything between AI-691a marker
        // and the next major comment/closing-style) and confirm the
        // selector always has the .mw-content-picker-modal prefix.
        $start = strpos($this->blade, 'AI-691a (task-2026-05-16-860f75)');
        $this->assertNotFalse($start, 'AI-691a task-id marker must be present.');
        // task-bc28fd CHANGE: after the relocation to
        // live-edit-classes.css the host file is CSS not Blade, so
        // there's no `</style>` close tag. Slice from the marker to
        // either the next AI block comment (`/* AI-`) or EOF.
        $afterMarker = $start + strlen('AI-691a (task-2026-05-16-860f75)');
        $nextBlock = strpos($this->blade, '/* AI-', $afterMarker);
        $end = $nextBlock !== false ? $nextBlock : strlen($this->blade);
        $slice = substr($this->blade, $start, $end - $start);
        // Any CSS RULE line (containing `{`) that mentions
        // `.fi-modal-footer-actions` must also be preceded by
        // `.mw-content-picker-modal` on the same line. Skip prose
        // lines (comment text that mentions the selector for
        // documentation) — they don't affect the cascade. This is
        // the LESSONS guard-self-match pattern: a test scanning for
        // selectors must filter out the test/source comments that
        // describe those selectors, otherwise the guard self-matches
        // on its own prose.
        $lines = preg_split('/\r?\n/', $slice);
        foreach ($lines as $line) {
            if (!str_contains($line, '{')) {
                continue;
            }
            if (str_contains($line, '.fi-modal-footer-actions')) {
                $this->assertStringContainsString(
                    '.mw-content-picker-modal',
                    $line,
                    "AI-691a CSS rule must scope .fi-modal-footer-actions with .mw-content-picker-modal — found unscoped selector line: {$line}"
                );
            }
        }
    }

    #[Test]
    public function rule_does_not_appear_in_mobile_only_css_file(): void
    {
        // Regression guard: the desktop-min-width rule MUST NOT land in
        // `live-edit-mobile.css` which is the mobile-only overrides
        // pipeline. Mobile-file lives under `@media (max-width: 768px)`
        // wrappers; a min-width: 769px rule there would be dead code at
        // best and a maintenance trap at worst.
        $this->assertStringNotContainsString(
            'min-width: 769px',
            $this->mobileCss,
            'AI-691a desktop-only rule must NOT appear in live-edit-mobile.css.'
        );
    }

    #[Test]
    public function php_cancel_action_label_is_still_set(): void
    {
        // Path B is CSS-only — the PHP layer is untouched. Mobile users
        // still render the Cancel button via Filament's modal chrome.
        // If a future agent deletes this line thinking AI-691a covered
        // it, mobile regresses to NOVICE #3 (picker "stuck open").
        $this->assertStringContainsString(
            "->modalCancelActionLabel('Cancel')",
            $this->page,
            'Path B keeps the PHP cancel-label intact — mobile relies on it. '
            . 'Removal is bound to AI-695 mobile bottom-sheet ship; do not pre-remove.'
        );
    }

    #[Test]
    public function task_id_marker_and_path_b_rationale_present(): void
    {
        // Audit-grep: source-side comment must carry the task-id and
        // the §A6 Path B rationale + NOVICE #3 cross-reference.
        $this->assertStringContainsString('task-2026-05-16-860f75', $this->blade);
        $this->assertStringContainsString('AI-691a', $this->blade);
        $this->assertStringContainsString('Path B', $this->blade);
        $this->assertStringContainsString('NOVICE #3', $this->blade);
        $this->assertStringContainsString('AI-695', $this->blade,
            'AI-695 binding note (Cancel goes away when bottom-sheet ships) must appear in the source comment.');
    }

    #[Test]
    public function picker_scope_class_still_emitted_on_addcontentaction(): void
    {
        // The CSS rule depends on Filament rendering the modal with
        // `mw-content-picker-modal` in `extraModalWindowAttributes`.
        // If that hook is ever removed, the rule silently stops
        // applying — pin both halves of the contract together.
        $this->assertMatchesRegularExpression(
            "/->extraModalWindowAttributes\\(\\[\\s*'class'\\s*=>\\s*'[^']*mw-content-picker-modal/",
            $this->page,
            'addContentAction must keep `mw-content-picker-modal` in extraModalWindowAttributes — AI-691a scope hinges on it.'
        );
    }

    #[Test]
    public function modal_submit_action_remains_disabled(): void
    {
        // The "hide the whole .fi-modal-footer-actions wrapper"
        // technique is correct ONLY because the footer contains a
        // single button (Cancel). If a future agent adds a submit
        // action, this rule would hide it too — and AI-691a needs
        // to switch to a Cancel-specific selector. Pin the
        // modalSubmitAction(false) hook so the assumption fails
        // loudly if it changes.
        $this->assertMatchesRegularExpression(
            '/addContentAction[\s\S]*?->modalSubmitAction\(false\)/s',
            $this->page,
            'addContentAction must keep modalSubmitAction(false) — AI-691a hides the whole footer-actions wrapper on the assumption that Cancel is the only button.'
        );
    }
}
