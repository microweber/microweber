<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-151 / AI-174 — Live-edit save toast lacks visual confirmation.
 *
 * UX-audit finding (AI-174 Low): when the user clicks "Save" in
 * Live Edit, the only feedback was the existing
 * `mw.notification.success('Page saved successfully.', 7500)` toast
 * — a plain text sentence with no icon or visual emphasis. Users
 * reported being uncertain whether their work had committed.
 *
 * Cycle-151 fix (CSS + Vue, no behavioural change):
 *   1. SaveButton.vue (`packages/frontend-assets/.../Toolbar/
 *      SaveButton.vue`) emits a richer toast with an inline SVG
 *      checkmark + a "Page saved" label wrapped in
 *      `.mw-notification-saved-toast` so the toast reads as a
 *      deliberate confirmation.
 *   2. The matching `.mw-notification-saved-toast` /
 *      `__icon` / `__label` rules ship in two CSS bundles:
 *      - `packages/microweber-filament-theme/.../general-styles.css`
 *        (admin chrome — where mw.notification appends to
 *        window.top.document.body);
 *      - `packages/frontend-assets/.../microweber/css/ui.css`
 *        (canvas iframe context — defensive duplicate so the
 *        toast looks correct even if a future surface fires it).
 *   3. The polyline ✓ glyph uses `currentColor` so it inherits the
 *      .mw-success / text-bg-success green chrome the toast already
 *      carries.
 */
class Ai174LiveEditSaveToastCheckmarkContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_174_anchor(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        $this->assertStringContainsString('AI-174', $vue,
            'SaveButton.vue MUST carry the AI-174 anchor inline.');
        $this->assertStringContainsString('cycle-151', $vue,
            'SaveButton.vue MUST carry the cycle-151 anchor inline.');

        $css = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css');
        $this->assertStringContainsString('AI-174', $css,
            'general-styles.css MUST carry the AI-174 anchor inline.');
    }

    #[Test]
    public function save_button_emits_html_with_checkmark_svg(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');

        // The success branch MUST emit a richer HTML toast wrapped in
        // .mw-notification-saved-toast with an SVG icon class.
        $this->assertMatchesRegularExpression(
            '/mw-notification-saved-toast/',
            $vue,
            'SaveButton.vue MUST emit toast HTML containing the '
            . '.mw-notification-saved-toast wrapper class.'
        );
        $this->assertMatchesRegularExpression(
            '/mw-notification-saved-toast__icon/',
            $vue,
            'SaveButton.vue MUST tag the inline SVG with the '
            . '__icon BEM class so the styling rule binds.'
        );
        $this->assertMatchesRegularExpression(
            '/mw-notification-saved-toast__label/',
            $vue,
            'SaveButton.vue MUST wrap the "Page saved" text in a '
            . '__label BEM span so the styling rule binds.'
        );
    }

    #[Test]
    public function svg_uses_polyline_checkmark(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        // Pin the polyline path that draws the ✓ — guarantees the
        // visual checkmark, not some other SVG glyph.
        $this->assertMatchesRegularExpression(
            '/mw-notification-saved-toast__icon[\s\S]{0,500}<polyline\s+points="20\s+6\s+9\s+17\s+4\s+12"/m',
            $vue,
            'SaveButton.vue checkmark SVG MUST use the canonical '
            . '20,6 9,17 4,12 polyline so the ✓ glyph renders.'
        );
    }

    #[Test]
    public function svg_is_aria_hidden(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        // The wrapper text "Page saved" is the announceable label;
        // the SVG is presentational so MUST be aria-hidden.
        $this->assertMatchesRegularExpression(
            '/mw-notification-saved-toast__icon[\s\S]{0,500}aria-hidden="true"/m',
            $vue,
            'SaveButton.vue checkmark SVG MUST be aria-hidden="true" '
            . 'so screen readers don\'t double-announce a graphic.'
        );
    }

    #[Test]
    public function label_text_is_page_saved(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        $this->assertMatchesRegularExpression(
            '/mw-notification-saved-toast__label">Page saved</m',
            $vue,
            'SaveButton.vue MUST render the literal "Page saved" '
            . 'string inside the .__label span — that\'s the AI-174 '
            . 'ticket-prescribed user-facing text.'
        );
    }

    #[Test]
    public function admin_chrome_css_pins_saved_toast_layout(): void
    {
        $css = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css');
        // Inline-flex + gap so the icon + label sit side-by-side.
        $this->assertMatchesRegularExpression(
            '/\.mw-notification-saved-toast\s*\{[\s\S]{0,400}display:\s*inline-flex/m',
            $css,
            'general-styles.css MUST pin display:inline-flex on '
            . '.mw-notification-saved-toast so icon + label sit on the '
            . 'same row inside the toast pill.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-notification-saved-toast\s*\{[\s\S]{0,400}gap:\s*0\.5rem/m',
            $css,
            'general-styles.css MUST pin gap:0.5rem so the icon and '
            . 'label have breathing room.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-notification-saved-toast__icon\s*\{[\s\S]{0,400}width:\s*20px[\s\S]{0,200}height:\s*20px/m',
            $css,
            'general-styles.css MUST pin the .__icon to 20x20 so the '
            . 'checkmark is visually balanced with the label text.'
        );
    }

    #[Test]
    public function built_filament_theme_bundle_carries_saved_toast_rules(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing; skipping production-CSS pin.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson: every load-bearing piece
        // MUST appear in the built bundle. Source-only landings caused
        // the 16-cycle live-edit-mobile.css orphan regression.
        $this->assertStringContainsString('.mw-notification-saved-toast',
            $built,
            'Built filament-theme bundle MUST contain the saved-toast '
            . 'wrapper rule. If missing, the bundle was not rebuilt '
            . 'after the source edit.'
        );
        $this->assertStringContainsString('.mw-notification-saved-toast__icon',
            $built,
            'Built filament-theme bundle MUST contain the saved-toast '
            . '.__icon rule.'
        );
    }

    #[Test]
    public function built_live_edit_app_js_carries_page_saved_html(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built live-edit-app.js missing; skipping production-bundle pin.");
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString('mw-notification-saved-toast', $built,
            'Built live-edit-app.js MUST reference the .mw-notification-saved-toast '
            . 'class. If missing, the bundle was not rebuilt after the '
            . 'SaveButton.vue source edit.');
        $this->assertStringContainsString('Page saved', $built,
            'Built live-edit-app.js MUST contain the "Page saved" label '
            . 'text emitted by SaveButton.vue.');
    }
}
