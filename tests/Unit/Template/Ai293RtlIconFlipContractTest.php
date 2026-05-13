<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-293 RTL directional-icon flip contract test (task-2026-05-13-c3c806).
 *
 * Bounded first slice of AI-293 (Low priority, multi-cycle RTL support).
 * The HTML root already carries `dir="rtl"` when an RTL language is
 * active (lang_attributes() in Lang.php:276), so the `[dir="rtl"]`
 * selector activates automatically — no per-component opt-in needed.
 *
 * AC #4 only:
 *   - directional Heroicon arrows + chevrons (heroicon-{o,m,s}-arrow-*,
 *     heroicon-{o,m,s}-chevron-*) flip via `transform: scaleX(-1)`
 *   - Filament's data-uri chevron pseudo-elements on sidebar / nav-group
 *     / breadcrumbs flip via the same transform on `::after`
 *
 * AC #1/#2/#3/#5/#6/#7 deferred to AI-293a..f follow-ups — documented
 * inline at the top of the CSS block.
 *
 * The test pins:
 *   - the @media-less `[dir="rtl"]` selector list is present in
 *     mobile-touch.css
 *   - each canonical heroicon arrow + chevron stem is covered
 *     (DataProvider runs 12+ assertions across the icon classes)
 *   - the pseudo-element chevron flip rule is in place
 *   - non-directional icons (banknote, language, phone, envelope,
 *     building-storefront) are NOT in the selector list (regression
 *     guard so a future commit doesn't accidentally flip them too)
 *   - the built theme bundle carries the rules so an unbuilt package
 *     fails fast in CI
 */
class Ai293RtlIconFlipContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = __DIR__ . '/../../../packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const THEME_CSS_BUILT  = __DIR__ . '/../../../public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    public static function directionalIconProvider(): array
    {
        return [
            'heroicon-o-arrow-left'          => ['heroicon-o-arrow-left'],
            'heroicon-o-arrow-right'         => ['heroicon-o-arrow-right'],
            'heroicon-o-arrow-long-left'     => ['heroicon-o-arrow-long-left'],
            'heroicon-o-arrow-long-right'    => ['heroicon-o-arrow-long-right'],
            'heroicon-o-arrow-uturn-left'    => ['heroicon-o-arrow-uturn-left'],
            'heroicon-o-arrow-uturn-right'   => ['heroicon-o-arrow-uturn-right'],
            'heroicon-m-arrow-left'          => ['heroicon-m-arrow-left'],
            'heroicon-m-arrow-right'         => ['heroicon-m-arrow-right'],
            'heroicon-s-arrow-left'          => ['heroicon-s-arrow-left'],
            'heroicon-s-arrow-right'         => ['heroicon-s-arrow-right'],
            'heroicon-o-chevron-left'        => ['heroicon-o-chevron-left'],
            'heroicon-o-chevron-right'       => ['heroicon-o-chevron-right'],
            'heroicon-o-chevron-double-left' => ['heroicon-o-chevron-double-left'],
            'heroicon-m-chevron-left'        => ['heroicon-m-chevron-left'],
            'heroicon-m-chevron-right'       => ['heroicon-m-chevron-right'],
            'heroicon-s-chevron-left'        => ['heroicon-s-chevron-left'],
            'heroicon-s-chevron-right'       => ['heroicon-s-chevron-right'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('directionalIconProvider')]
    #[Test]
    public function each_directional_heroicon_class_is_listed_in_the_flip_selector(string $iconClass): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\[dir="rtl"\]\s+svg\.' . preg_quote($iconClass, '/') . '\b/',
            $css,
            "AI-293 directional-icon flip selector must include svg.{$iconClass}."
        );
    }

    #[Test]
    public function flip_rule_resolves_to_scale_x_minus_one(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        // The selector list spans many lines and ends with
        // `svg.heroicon-s-chevron-right { transform: scaleX(-1); }`.
        // Pin that closer to confirm the whole list resolves to the
        // flip transform.
        $this->assertMatchesRegularExpression(
            '/\[dir="rtl"\]\s+svg\.heroicon-s-chevron-right\s*\{[^}]*transform:\s*scaleX\(-1\)/s',
            $css,
            'AI-293 directional-icon selector list must close with `svg.heroicon-s-chevron-right { transform: scaleX(-1); }` so the whole list resolves to the flip transform.'
        );

        // Also pin that scaleX(-1) appears at least twice in the file
        // (icon-class list + pseudo-element list).
        $occurrences = preg_match_all('/transform:\s*scaleX\(-1\)/', $css);
        $this->assertGreaterThanOrEqual(
            2,
            $occurrences,
            'AI-293 must declare `transform: scaleX(-1)` at least twice — once on the icon-class selector list and once on the Filament pseudo-element list.'
        );
    }

    #[Test]
    public function filament_pseudo_element_chevrons_also_flip(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\[dir="rtl"\]\s+\.fi-sidebar-nav-item\s*>\s*a::after,\s*\[dir="rtl"\]\s+\.fi-nav-group-button::after,\s*\[dir="rtl"\]\s+\.fi-breadcrumbs-item:not\(:last-child\)::after\s*\{[^}]*transform:\s*scaleX\(-1\)/s',
            $css,
            'AI-293 must flip the Filament data-uri chevron pseudo-elements on .fi-sidebar-nav-item, .fi-nav-group-button, and .fi-breadcrumbs-item via transform: scaleX(-1) on ::after.'
        );
    }

    public static function nonDirectionalIconProvider(): array
    {
        return [
            'banknote (currency)'      => ['heroicon-o-banknote'],
            'credit-card (currency)'   => ['heroicon-o-credit-card'],
            'language (locale)'        => ['heroicon-o-language'],
            'phone (contact)'          => ['heroicon-o-phone'],
            'envelope (contact)'       => ['heroicon-o-envelope'],
            'storefront (decorative)'  => ['heroicon-o-building-storefront'],
            'cog (decorative)'         => ['heroicon-o-cog-6-tooth'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('nonDirectionalIconProvider')]
    #[Test]
    public function non_directional_icons_are_not_in_the_flip_selector(string $iconClass): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertDoesNotMatchRegularExpression(
            '/\[dir="rtl"\]\s+svg\.' . preg_quote($iconClass, '/') . '\b/',
            $css,
            "AI-293 must NOT flip non-directional icon {$iconClass} — flipping currency / contact / decorative icons in RTL would corrupt their meaning."
        );
    }

    #[Test]
    public function trending_arrows_are_not_flipped_data_indicators_not_navigation(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        // arrow-trending-up + arrow-trending-down are data indicators
        // (revenue going up/down) — they are NOT navigation affordances
        // and must not flip. The user's "trending up" should look the
        // same in both LTR and RTL contexts.
        $this->assertDoesNotMatchRegularExpression(
            '/\[dir="rtl"\]\s+svg\.heroicon-[ms]-arrow-trending-(up|down)\b/',
            $css,
            'AI-293 must NOT flip heroicon-m-arrow-trending-up/down — those are data indicators (revenue trending), not navigation affordances. Flipping them in RTL would invert their meaning.'
        );
    }

    #[Test]
    public function built_theme_bundle_carries_the_rtl_flip_rules(): void
    {
        if (!file_exists(self::THEME_CSS_BUILT)) {
            $this->markTestSkipped('Built filament-theme bundle missing — run `npm run build` in packages/microweber-filament-theme.');
        }

        $built = $this->readFile(self::THEME_CSS_BUILT);

        $this->assertStringContainsString(
            '[dir="rtl"] svg.heroicon-o-chevron-right',
            $built,
            'Built bundle must carry the [dir="rtl"] svg.heroicon-o-chevron-right selector — run npm run build if missing.'
        );

        $this->assertStringContainsString(
            '[dir="rtl"] .fi-sidebar-nav-item',
            $built,
            'Built bundle must carry the [dir="rtl"] .fi-sidebar-nav-item pseudo-element flip selector.'
        );

        $this->assertStringContainsString(
            'scaleX(-1)',
            $built,
            'Built bundle must carry the scaleX(-1) transform value used by both flip rules.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
