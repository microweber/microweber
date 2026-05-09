<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-146 / AI-168 + AI-169 — admin dark-mode toggle + public
 * hamburger 44x44 (Big2-class corrigendum).
 *
 *   - AI-169: cycle-144 default.css selector list missed Big2's actual
 *     hamburger class. Big2 ships its public hamburger as
 *     `.mw-vhmbgr-wrapper` (a Microweber-namespaced class), NOT
 *     Bootstrap's `.navbar-toggler`. The cycle-144 rule fired against
 *     no element on Big2. Cycle-146 extends the selector list to
 *     cover the Microweber-namespaced variants and bumps to
 *     `!important` so Big2's higher-specificity `min-width: auto`
 *     declaration on the same element loses the cascade fight.
 *
 *   - AI-168: tester audit at 390x844 reported no dark-mode toggle in
 *     the admin shell. Filament v5 ships its theme-switcher inside
 *     the user-menu dropdown by default
 *     (vendor/filament/filament/resources/views/components/user-menu.blade.php
 *     line 112-116). Whatever customisation in this app's user-menu
 *     stack drops the theme-switcher item. Rather than debug that
 *     view-override path, cycle-146 injects a topbar-end render
 *     hook that emits the exact same Filament <x-filament-panels::
 *     theme-switcher /> component into the topbar — so the toggle is
 *     visible at the same level as the Notifications bell.
 */
class Ai168Ai169AdminThemeAndHamburgerContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    // -----------------------------------------------------------------
    // AI-169 — public hamburger 44x44 floor across Big2 + Bootstrap
    // -----------------------------------------------------------------

    #[Test]
    public function default_css_selector_list_includes_big2_hamburger_class(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        // The rule MUST cover Big2's actual hamburger class. Cycle-144's
        // .navbar-toggler / .menu-toggle / .mw-mobile-menu-toggle list
        // missed the Microweber-namespaced variant Big2 ships with.
        foreach ([
            '.navbar-toggler',
            '.mw-vhmbgr-wrapper',
            '.mw-hamburger',
            '.mw-hamburger-button',
            '.navbar-burger',
        ] as $selector) {
            $this->assertStringContainsString(
                $selector,
                $src,
                "default.css 44x44 hamburger selector list MUST include "
                . "{$selector} so the public hamburger floor catches "
                . 'all common class chains shipped across templates.'
            );
        }
    }

    #[Test]
    public function default_css_hamburger_rule_uses_important_to_win_cascade(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        // Tester re-test confirmed Big2 ships `min-width: auto;
        // min-height: auto` at higher specificity. The cycle-146 rule
        // MUST use `!important` to win that cascade fight — otherwise
        // the floor is overridden and the hamburger stays at 25x25.
        $this->assertMatchesRegularExpression(
            '/\.mw-vhmbgr-wrapper[\s\S]{0,200}min-width:\s*44px\s*!important/m',
            $src,
            'default.css MUST use min-width: 44px !important on the '
            . 'hamburger selector list so Big2\'s `min-width: auto` '
            . 'declaration loses the cascade.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-vhmbgr-wrapper[\s\S]{0,200}min-height:\s*44px\s*!important/m',
            $src,
            'default.css MUST use min-height: 44px !important too.'
        );
    }

    #[Test]
    public function ai_169_anchor_documents_the_corrigendum(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        $this->assertStringContainsString(
            'AI-169',
            $src,
            'default.css MUST carry the AI-169 anchor inline so a '
            . 'future maintainer can trace the cycle-146 selector '
            . 'corrigendum.'
        );
        $this->assertStringContainsString(
            'cycle-146',
            $src,
            'default.css MUST carry the cycle-146 anchor inline.'
        );
    }

    #[Test]
    public function built_default_css_carries_the_corrected_selector(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/default.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped(
                "Built bundle not present at {$rel}; skipping production-CSS pin. "
                . 'Run `npm run build` in packages/frontend-assets/ to refresh.'
            );
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString(
            'mw-vhmbgr-wrapper',
            $built,
            'Built default.css MUST contain the Big2 hamburger class '
            . 'in the 44x44 floor selector list. If this fails, the '
            . 'public-side bundle was not rebuilt after the source '
            . 'edit (the user instruction "always run the build" rule).'
        );

        $this->assertMatchesRegularExpression(
            '/min-width:\s*44px\s*!important/m',
            $built,
            'Built default.css MUST contain the !important variant of '
            . 'the floor rule so Big2\'s cascade-overriding min-width: '
            . 'auto cannot defeat it.'
        );
    }

    // -----------------------------------------------------------------
    // AI-168 — admin dark-mode topbar toggle
    // -----------------------------------------------------------------

    #[Test]
    public function microweber_filament_theme_registers_topbar_end_render_hook(): void
    {
        $src = $this->read('packages/microweber-filament-theme/src/MicroweberFilamentTheme.php');

        // The cycle-146 fix injects the Filament theme-switcher into
        // the topbar-end render hook so the toggle is visible at the
        // same level as the Notifications bell on every viewport
        // (including mobile where the user-menu version was missing).
        $this->assertMatchesRegularExpression(
            '/PanelsRenderHook::TOPBAR_END[\s\S]{0,400}theme-switcher/m',
            $src,
            'MicroweberFilamentTheme::boot MUST register a render hook '
            . 'on PanelsRenderHook::TOPBAR_END that emits a Filament '
            . 'theme-switcher component — so the dark-mode toggle is '
            . 'visible alongside the Notifications bell on mobile.'
        );
    }

    #[Test]
    public function topbar_render_hook_uses_filament_theme_switcher_component(): void
    {
        $src = $this->read('packages/microweber-filament-theme/src/MicroweberFilamentTheme.php');

        // The topbar render hook MUST emit the canonical Filament
        // <x-filament-panels::theme-switcher /> component so the toggle
        // shares Alpine state + localStorage.theme key with whatever
        // user-menu version Filament might still render. Both surfaces
        // toggle the same `dark` class on <html>.
        $this->assertMatchesRegularExpression(
            '/<x-filament-panels::theme-switcher\s*\/>/',
            $src,
            'MicroweberFilamentTheme::boot MUST emit the canonical '
            . 'Filament theme-switcher Blade component (not a custom '
            . 'rebuild) so the topbar toggle stays in lockstep with '
            . 'Filament\'s own theme state machinery.'
        );
    }

    #[Test]
    public function ai_168_anchor_documents_the_topbar_injection(): void
    {
        $src = $this->read('packages/microweber-filament-theme/src/MicroweberFilamentTheme.php');

        $this->assertStringContainsString(
            'AI-168',
            $src,
            'MicroweberFilamentTheme MUST carry the AI-168 anchor '
            . 'inline so the cycle-146 render-hook decision is '
            . 'discoverable at refactor time.'
        );
    }

    #[Test]
    public function built_filament_theme_bundle_was_rebuilt(): void
    {
        // The MicroweberFilamentTheme.php change is PHP-only (a render
        // hook closure), so the BUILT CSS bundle doesn't change for
        // this fix. But we still want a sanity check that the package
        // build pipeline is intact + the bundle exists. If the bundle
        // is missing, the FilamentAsset registration in this same
        // class fails and the rest of the admin theme breaks.
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        $this->assertFileExists(
            $path,
            'Built filament-theme bundle MUST exist at the canonical '
            . 'public/vendor path. If missing, FilamentAsset::register '
            . 'will fail to bind the theme and admin chrome will break.'
        );
        $this->assertGreaterThan(
            10000,
            filesize($path),
            'Built filament-theme.css MUST be substantively populated '
            . '(>10KB) — a tiny stub indicates a broken build.'
        );
    }
}
