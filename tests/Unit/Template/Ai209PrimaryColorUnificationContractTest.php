<?php

namespace Tests\Unit\Template;

use MicroweberPackages\Admin\Filament\MwColors;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-209 primary color unification contract test (task-2026-05-13-e8ebcf).
 *
 * tester reported four different blues for primary CTAs across the
 * surfaces:
 *   - /shop "All Categories"  → #0d6efd (Bootstrap blue)
 *   - /checkout "Next"        → #4299e1 (Tailwind cyan — Color::Blue)
 *   - /checkout "Place Order" → #2fb344 (semantic-success green, OK)
 *   - /admin   "Save"         → admin panel primary (was MwColors::Blue
 *     anchored at #4991FC, also distinct from Bootstrap)
 *
 * The fix unifies admin + checkout panels on a shared `MwColors::Blue`
 * palette whose 500-weight slot equals Bootstrap's `--color-primary`
 * (#0d6efd / RGB 13, 110, 253). The full ladder mirrors Bootstrap's
 * blue tokens so hover/active states stay in palette.
 *
 * The Place Order green is semantic (`->color('success')`) and stays
 * intentionally distinct — that's NOT a regression.
 *
 * This test pins:
 *   - MwColors::Blue 500 slot exactly equals "13, 110, 253"
 *   - the rest of the ladder matches Bootstrap's blue tokens (100→900)
 *     so a future palette tweak that drifts from the Bootstrap source
 *     of truth fails fast
 *   - the canonical design-system token `--color-primary` is `#0d6efd`
 *     (regression guard so no future "let's pick a nicer blue" PR can
 *     desync the Filament palette from the public-side var)
 *   - the FilamentCheckoutPanelProvider's `colors()` call uses
 *     `MwColors::Blue` (NOT `Color::Blue`) — the actual regression that
 *     produced the cyan checkout button.
 */
class Ai209PrimaryColorUnificationContractTest extends TestCase
{
    private const MW_COLORS_PHP             = __DIR__ . '/../../../src/MicroweberPackages/Admin/Filament/MwColors.php';
    private const CHECKOUT_PANEL_PROVIDER   = __DIR__ . '/../../../Modules/Checkout/Providers/FilamentCheckoutPanelProvider.php';
    private const ADMIN_PANEL_PROVIDER      = __DIR__ . '/../../../src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php';
    private const DESIGN_SYSTEM_CSS         = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS          = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    #[Test]
    public function mw_colors_blue_500_slot_matches_bootstrap_color_primary(): void
    {
        // Sanity: the constant loads and is an associative array keyed
        // by 50/100/.../950.
        $this->assertIsArray(MwColors::Blue);
        $this->assertArrayHasKey(500, MwColors::Blue);

        $this->assertSame(
            '13, 110, 253',
            MwColors::Blue[500],
            'MwColors::Blue[500] must exactly equal "13, 110, 253" (RGB for #0d6efd / Bootstrap blue-500 / --color-primary). Drift here means the Filament admin + checkout primary buttons no longer match the public Bootstrap CTAs.'
        );
    }

    /**
     * The full Bootstrap-blue ladder so a future tweak that re-derives
     * the palette from a different anchor fails fast. Keys 100..900
     * mirror Bootstrap's documented blue tokens; 50 + 950 are the
     * tint / shade extensions Filament needs but Bootstrap doesn't
     * publish.
     */
    public static function bootstrapBlueLadderProvider(): array
    {
        return [
            '50  pre-tint'                => [50,  '231, 241, 255'],
            '100 Bootstrap blue-100'      => [100, '207, 226, 255'],
            '200 Bootstrap blue-200'      => [200, '158, 197, 254'],
            '300 Bootstrap blue-300'      => [300, '110, 168, 254'],
            '400 Bootstrap blue-400'      => [400, '61, 139, 253'],
            '500 Bootstrap blue-500'      => [500, '13, 110, 253'],
            '600 Bootstrap blue-600'      => [600, '10, 88, 202'],
            '700 Bootstrap blue-700'      => [700, '8, 66, 152'],
            '800 Bootstrap blue-800'      => [800, '5, 44, 101'],
            '900 Bootstrap blue-900'      => [900, '3, 22, 51'],
            '950 post-shade'              => [950, '1, 10, 25'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('bootstrapBlueLadderProvider')]
    #[Test]
    public function mw_colors_blue_full_ladder_mirrors_bootstrap_tokens(int $weight, string $expected): void
    {
        $this->assertArrayHasKey($weight, MwColors::Blue);
        $this->assertSame(
            $expected,
            MwColors::Blue[$weight],
            "MwColors::Blue[{$weight}] must equal '{$expected}' so the Filament palette stays anchored to Bootstrap's blue ladder."
        );
    }

    #[Test]
    public function design_system_color_primary_token_is_0d6efd_bootstrap_blue(): void
    {
        $css = $this->readFile(self::DESIGN_SYSTEM_CSS);

        $this->assertMatchesRegularExpression(
            '/--color-primary:\s*#0d6efd\s*;/i',
            $css,
            'design-system.css must continue to define --color-primary: #0d6efd; — drift here desynchronises the public Bootstrap CTAs from the Filament panels that now anchor on this value.'
        );
    }

    #[Test]
    public function public_touch_color_primary_token_mirrors_design_system(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/--color-primary:\s*#0d6efd\s*;/i',
            $css,
            'Templates/Bootstrap/.../public-touch.css must mirror --color-primary: #0d6efd; from the canonical design-system.css source.'
        );
    }

    #[Test]
    public function checkout_panel_provider_uses_mw_colors_blue_not_filament_color_blue(): void
    {
        $php = $this->readFile(self::CHECKOUT_PANEL_PROVIDER);

        $this->assertMatchesRegularExpression(
            '/->colors\(\[\s*\'primary\'\s*=>\s*MwColors::Blue\s*,?\s*\]\)/s',
            $php,
            'FilamentCheckoutPanelProvider must register primary => MwColors::Blue inside ->colors() so the checkout panel paints the unified Bootstrap-blue palette.'
        );

        $this->assertDoesNotMatchRegularExpression(
            '/->colors\(\[\s*\'primary\'\s*=>\s*Color::Blue\s*,?\s*\]\)/s',
            $php,
            'FilamentCheckoutPanelProvider must NOT use Color::Blue (Tailwind blue-500 #3b82f6) — that was the AI-209 regression. Use MwColors::Blue instead.'
        );

        // Confirm the import statement is also in place so the class
        // resolves without an autoload failure at panel boot.
        $this->assertStringContainsString(
            'use MicroweberPackages\Admin\Filament\MwColors;',
            $php,
            'FilamentCheckoutPanelProvider must import MwColors so MwColors::Blue resolves without a Class not found error.'
        );
    }

    #[Test]
    public function admin_panel_provider_keeps_mw_colors_blue(): void
    {
        $php = $this->readFile(self::ADMIN_PANEL_PROVIDER);

        $this->assertMatchesRegularExpression(
            '/->colors\(\[[\s\S]*?\'primary\'\s*=>\s*MwColors::Blue/s',
            $php,
            'FilamentAdminPanelProvider must continue to register primary => MwColors::Blue (AI-209 regression guard — the admin panel is the original consumer of the custom palette).'
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
