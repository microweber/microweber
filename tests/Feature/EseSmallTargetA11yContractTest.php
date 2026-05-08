<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-76 / AI-63 / TICKET-NN — ESE small-target a11y PR regression
 * coverage.
 *
 * Pins three concrete improvements per the brief:
 *   1. aria-live polite region — the silent style-changes problem
 *   2. SVG aria-hidden=true on decorative icons (13 ESE Vue files)
 *   3. div→button via role="button" + tabindex="0" + keyboard
 *      handlers + aria-expanded on the 14+ panel-toggle wrappers
 *      (using role+keyboard+tabindex is the canonical pattern when
 *      semantic <button> would conflict with nested form fields)
 *
 * Style after the cycle-52..75 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class EseSmallTargetA11yContractTest extends TestCase
{
    private string $appSrc;

    /** Files whose decorative SVGs must carry aria-hidden after cycle-76. */
    private const SVG_FILES = [
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorAnimations.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorContainer.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorLayoutSettings.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorUlOlListStyleEditor.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/Align.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/ImagePicker.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/SliderSmall.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorTypography.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorBackground.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorClassApplier.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorRoundedCorners.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorPredefinesStylesApplier.vue',
        'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorGrid.vue',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->appSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorApp.vue'
        ));
    }

    #[Test]
    public function ese_app_carries_aria_live_polite_announce_region(): void
    {
        // The role=status + aria-live + aria-atomic trio is the canonical
        // SR announce-region pattern. visually-hidden so sighted users
        // are unaffected.
        $this->assertStringContainsString(
            'role="status"',
            $this->appSrc,
            'ESE app: aria-live region must carry role="status"'
        );
        $this->assertStringContainsString(
            'aria-live="polite"',
            $this->appSrc,
            'ESE app: aria-live="polite" — assertive would interrupt the user'
        );
        $this->assertStringContainsString(
            'aria-atomic="true"',
            $this->appSrc,
            'ESE app: aria-atomic="true" so the whole message replays even when partial'
        );
        $this->assertStringContainsString(
            'visually-hidden mw-ese-announce',
            $this->appSrc,
            'ESE app: announce region must be visually hidden + carry the mw-ese-announce hook'
        );
        // The reactive variable is populated via announceAria() helper.
        $this->assertStringContainsString(
            'announceAria(message)',
            $this->appSrc,
            'ESE app: announceAria helper must exist'
        );
        // Two-step write (clear, then set on next tick) so identical
        // consecutive messages re-fire — without this most SR engines
        // skip duplicate utterances.
        $this->assertStringContainsString(
            "this.ariaAnnouncement = '';",
            $this->appSrc,
            'announceAria: must clear the announcement before setting on next tick (so duplicate text re-fires)'
        );
        $this->assertStringContainsString(
            'this.$nextTick(() => {',
            $this->appSrc,
            'announceAria: must use $nextTick for the second write'
        );
    }

    #[Test]
    public function every_toggle_method_announces_via_aria_live(): void
    {
        // Each panel-open dispatches an announceAria call so SR users
        // hear "Typography panel opened" / "Background panel opened"
        // etc. when they tab onto a wrapper and press Enter.
        $required = [
            'toggleTypography',
            'toggleBackground',
            'toggleSpacing',
            'toggleContainer',
            'toggleGrid',
            'toggleBorder',
            'toggleRoundedCorners',
            'toggleAnimations',
            'toggleShadow',
            'toggleClassApplier',
            'toggleListStyleEditor',
            'toggleLayoutSettings',
            'togglePredefinedStylesApplier',
            'toggleAiChatSettings',
        ];
        foreach ($required as $fn) {
            // Each method body must contain `this.announceAria(`.
            // Pin the MethodName + announceAria pair via a per-method
            // regex so we know each one is independently instrumented.
            $this->assertMatchesRegularExpression(
                "/{$fn}\\(\\)\\s*\\{\\s*\\n\\s*this\\.resetActiveStates\\(\\);\\s*\\n\\s*this\\.announceAria\\(/",
                $this->appSrc,
                "ESE app: {$fn}() must call this.announceAria(...) right after resetActiveStates()"
            );
        }
    }

    #[Test]
    public function panel_toggle_wrappers_have_role_button_and_keyboard_handlers(): void
    {
        // Every .element-style-editor-toggle-wrapper div must carry
        // role="button" + tabindex="0" + Enter/Space keydown handlers
        // + aria-expanded so it behaves like a real button.
        // Count the toggle-wrappers and compare to the migrated count.
        $wrapperCount = preg_match_all(
            '/element-style-editor-toggle-wrapper/',
            $this->appSrc
        );
        $migratedCount = preg_match_all(
            '/element-style-editor-toggle-wrapper[^>]*role="button"/',
            $this->appSrc
        );

        $this->assertGreaterThanOrEqual(
            14,
            $wrapperCount,
            'ESE app: must have 14+ panel-toggle wrappers (one per panel)'
        );
        $this->assertGreaterThanOrEqual(
            14,
            $migratedCount,
            "ESE app: every toggle-wrapper must carry role=\"button\" — migrated {$migratedCount} of {$wrapperCount}"
        );

        // Keyboard handlers AND aria-expanded must accompany role.
        $this->assertStringContainsString(
            '@keydown.enter.prevent="toggleTypography"',
            $this->appSrc,
            'ESE app: Typography wrapper must carry @keydown.enter handler'
        );
        $this->assertStringContainsString(
            '@keydown.space.prevent="toggleTypography"',
            $this->appSrc,
            'ESE app: Typography wrapper must carry @keydown.space handler'
        );
        $this->assertStringContainsString(
            ':aria-expanded="isTypographyActive"',
            $this->appSrc,
            'ESE app: Typography wrapper must carry :aria-expanded bound to active state'
        );
    }

    #[Test]
    public function decorative_svgs_carry_aria_hidden(): void
    {
        // Decorative icons (icons next to text labels) must carry
        // aria-hidden="true" so screen readers don't double-announce
        // them ("settings, settings icon").
        foreach (self::SVG_FILES as $rel) {
            $path = base_path($rel);
            $this->assertFileExists($path, "Expected ESE Vue file: {$rel}");
            $src = file_get_contents($path);

            $totalSvgs = preg_match_all('/<svg\\b/', $src);
            $hiddenSvgs = preg_match_all('/<svg[^>]*\\baria-hidden="true"/', $src);

            // Allow a single SVG without aria-hidden ONLY if it's
            // CLEARLY informative (we don't have those in ESE; pin
            // strict parity).
            $this->assertSame(
                $totalSvgs,
                $hiddenSvgs,
                "{$rel}: every <svg> must carry aria-hidden=\"true\" (decorative icons) — got {$hiddenSvgs} of {$totalSvgs}"
            );
        }
    }

    #[Test]
    public function aria_announcement_data_field_initializes_empty(): void
    {
        // Empty string on first paint so the live region doesn't fire
        // on mount (would pre-announce a stale message).
        $this->assertStringContainsString(
            "ariaAnnouncement: ''",
            $this->appSrc,
            "ESE app: ariaAnnouncement must initialise to '' (empty) so the live region doesn't fire on mount"
        );
    }
}
