<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-150 / AI-173 — Module picker search lacks proper clear (X)
 * button on mobile.
 *
 * UX-audit finding (P3-001 / AI-173 Medium): the module-picker
 * search input (`<input>` inside `ListModules.vue`) had:
 *   - `type="text"` so mobile browsers could NOT render the native
 *     X clear-affordance;
 *   - a `<span>` clear control with inline `top:25px;right:23px;
 *     padding:3px;` styling that resolved to ~28x28 — under the
 *     WCAG 2.5.5 / iOS HIG 44x44 floor;
 *   - NO `aria-label`, NO keyboard activation (a `<span>` is not
 *     focusable by default and Enter/Space don't fire `click`).
 *
 * Cycle-150 fix:
 *   1. Switch input to `type="search"` + `inputmode="search"` +
 *      `enterkeyhint="search"` so mobile keyboards show a Search
 *      submit key + the browser renders its native X clear.
 *   2. Add `aria-label="Search modules"` so screen readers have a
 *      stable label that survives placeholder localisation.
 *   3. Convert the `<span>` clear-affordance to a real `<button
 *      type="button">` with `aria-label="Clear search"` so keyboard
 *      activation + focus rings + button semantics work natively.
 *   4. Add the `.mw-modules-list-search-clear` class with min-width:
 *      44px / min-height:44px so the explicit-clear hit target meets
 *      WCAG 2.5.5 + iOS HIG.
 *   5. Suppress the WebKit native `::-webkit-search-cancel-button`
 *      to avoid two visually-competing X glyphs in Chrome/Edge.
 *
 * Pattern mirrors the existing ListLayouts.vue search input.
 */
class Ai173ModulePickerSearchClearContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_173_anchor(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        $this->assertStringContainsString('AI-173', $src,
            'ListModules.vue MUST carry the AI-173 anchor inline so the '
            . 'cycle-150 search-clear fix is discoverable at refactor time.');
        $this->assertStringContainsString('cycle-150', $src,
            'ListModules.vue MUST carry the cycle-150 anchor inline.');
    }

    #[Test]
    public function input_is_type_search(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        // The search input MUST use type="search" so the browser renders
        // the native clear cross on mobile and the on-screen keyboard
        // shows a search submit key.
        $this->assertMatchesRegularExpression(
            '/<input\s+type="search"[\s\S]{0,400}js-modules-list-search-input/m',
            $src,
            'ListModules.vue search input MUST use type="search" so mobile '
            . 'browsers render the native X clear-affordance.'
        );
    }

    #[Test]
    public function input_carries_inputmode_and_enterkeyhint(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]{0,400}inputmode="search"/m',
            $src,
            'ListModules.vue search input MUST carry inputmode="search" so '
            . 'the on-screen keyboard shows the search variant.'
        );
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]{0,400}enterkeyhint="search"/m',
            $src,
            'ListModules.vue search input MUST carry enterkeyhint="search" '
            . 'so the on-screen keyboard submit key reads "search".'
        );
    }

    #[Test]
    public function input_has_aria_label(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        $this->assertMatchesRegularExpression(
            '/<input[\s\S]{0,400}:aria-label="\$lang\(\'Search modules\'\)"/m',
            $src,
            'ListModules.vue search input MUST carry a localised '
            . 'aria-label="Search modules" so screen readers have a '
            . 'stable label that survives placeholder localisation.'
        );
    }

    #[Test]
    public function clear_control_is_a_real_button_not_a_span(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        // The clear control MUST be a <button type="button"> — a <span>
        // is not focusable by default and Enter/Space don't fire click.
        $this->assertMatchesRegularExpression(
            '/<button\s+type="button"[\s\S]{0,400}mw-modules-list-search-clear[\s\S]{0,400}filterClearKeyword/m',
            $src,
            'ListModules.vue clear control MUST be a real <button type="button"> '
            . 'with the .mw-modules-list-search-clear class and the '
            . 'filterClearKeyword() click handler — replacing the cycle-N <span> '
            . 'that was not keyboard-accessible.'
        );
    }

    #[Test]
    public function clear_button_has_aria_label(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        $this->assertMatchesRegularExpression(
            '/<button[\s\S]{0,500}mw-modules-list-search-clear[\s\S]{0,400}:aria-label="\$lang\(\'Clear search\'\)"/m',
            $src,
            'ListModules.vue clear button MUST carry the localised '
            . 'aria-label="Clear search".'
        );
    }

    #[Test]
    public function svg_is_aria_hidden(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        // The SVG inside the clear button is a presentational icon —
        // the button already carries the aria-label so the SVG should
        // be aria-hidden so screen readers don't double-announce.
        $this->assertMatchesRegularExpression(
            '/<button[\s\S]{0,500}mw-modules-list-search-clear[\s\S]{0,400}<svg[\s\S]{0,400}aria-hidden="true"/m',
            $src,
            'ListModules.vue clear button SVG MUST be aria-hidden="true" '
            . 'so screen readers don\'t announce both the button label '
            . 'and a redundant graphic role.'
        );
    }

    #[Test]
    public function source_pins_44x44_touch_target_in_style(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        $this->assertMatchesRegularExpression(
            '/\.mw-modules-list-search-clear\s*\{[\s\S]{0,1000}min-width:\s*44px/m',
            $src,
            'ListModules.vue MUST pin min-width:44px on '
            . '.mw-modules-list-search-clear so the touch target meets '
            . 'WCAG 2.5.5 / iOS HIG 44x44.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-modules-list-search-clear\s*\{[\s\S]{0,1000}min-height:\s*44px/m',
            $src,
            'ListModules.vue MUST pin min-height:44px on '
            . '.mw-modules-list-search-clear.'
        );
    }

    #[Test]
    public function source_suppresses_native_webkit_clear_to_avoid_dupe(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/components/Modules/ListModules.vue');
        // The native WebKit X (Chrome/Edge) is suppressed because our
        // explicit button is the dominant + a11y-correct affordance.
        // Avoids two visually-competing X glyphs.
        $this->assertMatchesRegularExpression(
            '/\.mw-modules-list-search-input::-webkit-search-cancel-button[\s\S]{0,200}display:\s*none/m',
            $src,
            'ListModules.vue MUST suppress the native WebKit '
            . '::-webkit-search-cancel-button so it does NOT visually '
            . 'compete with the explicit .mw-modules-list-search-clear button.'
        );
    }

    #[Test]
    public function built_bundle_carries_search_attrs_and_clear_button(): void
    {
        $bundleJs = base_path('public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js');
        $bundleCss = base_path('public/vendor/microweber-packages/frontend-assets/build/live-edit-app.css');
        if (!file_exists($bundleJs)) {
            $this->markTestSkipped("Built JS bundle not present; skipping production-bundle pin.");
        }
        if (!file_exists($bundleCss)) {
            $this->markTestSkipped("Built CSS bundle not present; skipping production-bundle pin.");
        }
        $js = file_get_contents($bundleJs);
        $css = file_get_contents($bundleCss);

        // Functional pin per cycle-142 lesson: load-bearing pieces
        // MUST appear in the built bundle.
        $this->assertStringContainsString('"Clear search"', $js,
            'Built JS bundle MUST contain "Clear search" aria-label string.');
        $this->assertStringContainsString('"Search modules"', $js,
            'Built JS bundle MUST contain "Search modules" aria-label string.');
        $this->assertStringContainsString('mw-modules-list-search-clear', $js,
            'Built JS bundle MUST reference the .mw-modules-list-search-clear class.');

        $this->assertMatchesRegularExpression(
            '/\.mw-modules-list-search-clear[\s\S]{0,400}min-width:\s*44px/m',
            $css,
            'Built CSS bundle MUST contain min-width:44px on '
            . '.mw-modules-list-search-clear. If missing, the bundle '
            . 'was not rebuilt after the source edit.'
        );
        $this->assertStringContainsString('::-webkit-search-cancel-button', $css,
            'Built CSS bundle MUST contain the WebKit native-clear '
            . 'suppression rule.');
    }
}
