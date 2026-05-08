<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-77 / AI-64 / TICKET-PP — live-edit toolbar
 * `.btn-icon.live-edit-toolbar-buttons` div→button + 44x44
 * regression coverage.
 *
 * Pins:
 *   - All 5 `.btn-icon.live-edit-toolbar-buttons` toggles in
 *     SettingsCustomize.vue are real <button type="button">
 *     elements (not <div> or <span> with role="button"). Real
 *     <button> gets native focus ring, native form-submit
 *     semantics, and native keyboard activation.
 *   - The marker class `mw-toolbar-icon-btn` is added to each
 *     so the cycle-77 CSS sizing override scopes correctly.
 *   - The mobile-touch.css 44x44 sizing rule targets the marker
 *     class AND overrides the parent wrapper's max-h-[35px]
 *     constraint via the .mw-live-edit-resolutions-wrapper /
 *     .mw-live-edit-right-sidebar-wrapper specificity bump.
 *   - The previous role="button" + tabindex="0" pattern is gone
 *     (both attributes are now redundant on a real <button>).
 *
 * Style after the cycle-52..76 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class LiveEditToolbarButtonContractTest extends TestCase
{
    private string $vueSrc;
    private string $cssSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vueSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'
        ));
        $this->cssSrc = file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));
    }

    #[Test]
    public function five_toolbar_toggles_are_real_button_elements(): void
    {
        // Each toggle MUST be a real <button type="button">. Pin the
        // aria-label of each one so we know all 5 are present
        // AND that each one is on a <button>.
        $required = [
            'Insert layout',
            'Template settings',
            'Design',
            'Quick AI edit',
            'Advanced',
        ];
        foreach ($required as $label) {
            $this->assertMatchesRegularExpression(
                '/<button\\b[^>]*aria-label="' . preg_quote($label, '/') . '"/s',
                $this->vueSrc,
                "SettingsCustomize.vue: '{$label}' toolbar toggle must be a real <button> element"
            );
        }

        // type="button" MUST be present on each — without it,
        // <button> defaults to type="submit" and would submit any
        // ancestor form when clicked.
        $this->assertSame(
            5,
            substr_count($this->vueSrc, 'type="button"'),
            'SettingsCustomize.vue: must have exactly 5 type="button" attributes (one per toggle)'
        );
    }

    #[Test]
    public function div_role_button_pattern_is_gone_from_these_toggles(): void
    {
        // The previous shape used role="button" + tabindex="0" on a
        // <div> or <span>. With real <button> both attributes are
        // redundant — pin that they're gone from the migrated
        // toggles. (Other places in the file may still use role +
        // tabindex; we only forbid the pattern alongside
        // .btn-icon.live-edit-toolbar-buttons.)
        $this->assertDoesNotMatchRegularExpression(
            '/<(div|span)\\b[^>]*btn-icon\\s+live-edit-toolbar-buttons[^>]*role="button"/s',
            $this->vueSrc,
            'SettingsCustomize.vue: <div>/<span> with btn-icon.live-edit-toolbar-buttons + role="button" must NOT remain (real <button> takes its place)'
        );
    }

    #[Test]
    public function each_toggle_carries_the_marker_class(): void
    {
        // The mw-toolbar-icon-btn marker class is what the cycle-77
        // CSS targets. Without it, the wrapper's max-h-[35px] from
        // live-edit-classes.css line 162 would clamp the button to
        // 35px tall and the 44x44 floor would be lost.
        $this->assertSame(
            5,
            substr_count($this->vueSrc, 'mw-toolbar-icon-btn'),
            'SettingsCustomize.vue: must have exactly 5 mw-toolbar-icon-btn occurrences (one per toggle)'
        );
    }

    #[Test]
    public function mobile_touch_css_carries_44px_floor_for_marker_class(): void
    {
        // The new sizing rule must target the marker class with
        // min-width AND min-height of 44px AND override the parent
        // wrapper's max-h-[35px] via specificity bump.
        $this->assertStringContainsString(
            '.live-edit-toolbar-buttons.mw-toolbar-icon-btn',
            $this->cssSrc,
            'mobile-touch.css: must carry the .live-edit-toolbar-buttons.mw-toolbar-icon-btn selector'
        );
        $this->assertMatchesRegularExpression(
            '/\\.mw-live-edit-resolutions-wrapper\\s+\\.live-edit-toolbar-buttons\\.mw-toolbar-icon-btn/',
            $this->cssSrc,
            'mobile-touch.css: must scope a copy under .mw-live-edit-resolutions-wrapper to override the wrapper max-h-[35px]'
        );
        $this->assertMatchesRegularExpression(
            '/\\.mw-live-edit-right-sidebar-wrapper\\s+\\.live-edit-toolbar-buttons\\.mw-toolbar-icon-btn/',
            $this->cssSrc,
            'mobile-touch.css: must scope a copy under .mw-live-edit-right-sidebar-wrapper too'
        );

        // The actual 44px floor — both axes.
        $this->assertMatchesRegularExpression(
            '/\\.live-edit-toolbar-buttons\\.mw-toolbar-icon-btn[^{]*\\{[^}]*min-width:\\s*44px/s',
            $this->cssSrc,
            'mobile-touch.css: marker-class rule must apply min-width: 44px'
        );
        $this->assertMatchesRegularExpression(
            '/\\.live-edit-toolbar-buttons\\.mw-toolbar-icon-btn[^{]*\\{[^}]*min-height:\\s*44px/s',
            $this->cssSrc,
            'mobile-touch.css: marker-class rule must apply min-height: 44px'
        );
        // max-height bumped from the wrapper-imposed 35px to 44px so
        // the button can actually grow.
        $this->assertMatchesRegularExpression(
            '/\\.live-edit-toolbar-buttons\\.mw-toolbar-icon-btn[^{]*\\{[^}]*max-height:\\s*44px/s',
            $this->cssSrc,
            'mobile-touch.css: marker-class rule must override max-height to 44px (default wrapper clamps to 35px)'
        );
    }

    #[Test]
    public function rule_lives_outside_the_touch_viewport_media_block(): void
    {
        // The 44x44 rule applies at ALL viewports (icon-only buttons
        // benefit from the larger hit-area for keyboard + AT users
        // on desktop too) — pin that the rule is NOT nested inside
        // the touch-viewport media query at the top of the file.
        // We do this by finding the marker-class rule and verifying
        // it appears AFTER the closing brace of the touch-viewport
        // media block.
        $touchMediaPos = strpos($this->cssSrc, '@media (max-width: 1023.98px)');
        $this->assertNotFalse($touchMediaPos);

        // Find the closing brace of the touch-viewport block. Walk
        // forward counting braces.
        $depth = 0;
        $blockEnd = null;
        $started = false;
        for ($i = $touchMediaPos; $i < strlen($this->cssSrc); $i++) {
            if ($this->cssSrc[$i] === '{') {
                $depth++;
                $started = true;
            } elseif ($this->cssSrc[$i] === '}') {
                $depth--;
                if ($started && $depth === 0) {
                    $blockEnd = $i;
                    break;
                }
            }
        }
        $this->assertNotNull($blockEnd, 'Could not find end of touch-viewport @media block');

        $markerPos = strpos(
            $this->cssSrc,
            '.live-edit-toolbar-buttons.mw-toolbar-icon-btn'
        );
        $this->assertNotFalse($markerPos);
        $this->assertGreaterThan(
            $blockEnd,
            $markerPos,
            'mobile-touch.css: marker-class rule must live OUTSIDE the touch-viewport @media block (applies at all viewports)'
        );
    }
}
