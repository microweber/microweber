<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-515 — iOS notch + home-indicator safe-area coverage.
 *
 * Pins three coordinated shape facts:
 *   - The Live Edit canvas iframe viewport meta declares
 *     `viewport-fit=cover` so iOS Safari reports non-zero
 *     `env(safe-area-inset-*)` values inside the canvas.
 *   - mobile-touch.css contains the @supports-gated AI-515 block
 *     covering Filament topbars, page wrappers, sidebar nav, mw-modal
 *     close-X, Live Edit toolbar, and bottom action bars across both
 *     `admin` and `checkout` panels.
 *   - The rules use `env(safe-area-inset-*)` (not the deprecated
 *     `constant(safe-area-inset-*)`) and rely on `max(...)` so they
 *     stay inert on devices that report zero insets.
 *
 * Style: file-system reads only, no DB / Filament boot. Same pattern
 * as Big2MobileAuditContractTest, ModuleZooContractTest, etc.
 */
class Ai515MobileSafeAreaContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const LIVE_EDIT_IFRAME = 'src/MicroweberPackages/LiveEdit/resources/views/iframe.blade.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function live_edit_iframe_viewport_meta_declares_viewport_fit_cover(): void
    {
        $html = $this->read(self::LIVE_EDIT_IFRAME);

        // Must contain a viewport meta with viewport-fit=cover so iOS
        // Safari surfaces real safe-area-inset values inside the canvas.
        $this->assertMatchesRegularExpression(
            '/<meta\s+name="viewport"\s+content="[^"]*viewport-fit=cover[^"]*"/i',
            $html,
            'iframe.blade.php must declare viewport-fit=cover on its viewport meta tag.'
        );

        // The base width=device-width / initial-scale=1 contract must
        // still hold — viewport-fit was added, not substituted.
        $this->assertStringContainsString(
            'width=device-width',
            $html,
            'viewport meta must keep width=device-width.'
        );
        $this->assertStringContainsString(
            'initial-scale=1',
            $html,
            'viewport meta must keep initial-scale=1.'
        );
    }

    #[Test]
    public function mobile_touch_css_contains_ai515_supports_block(): void
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);

        $this->assertStringContainsString(
            '@supports (padding-top: env(safe-area-inset-top))',
            $css,
            'AI-515 rules must be gated behind an @supports feature query so legacy browsers skip them.'
        );

        // Marker comment is the project-canonical way to anchor a
        // contract test to a specific block — survives reformat passes.
        $this->assertStringContainsString(
            'AI-515',
            $css,
            'mobile-touch.css must carry the AI-515 marker comment for traceability.'
        );
    }

    /**
     * The four directional safe-area inset axes must all be referenced.
     * `top` covers the notch; `bottom` covers the home-indicator gesture
     * bar; `left` + `right` cover landscape-orientation rounded corners.
     */
    public static function safeAreaAxesProvider(): array
    {
        return [
            'top'    => ['env(safe-area-inset-top)'],
            'bottom' => ['env(safe-area-inset-bottom)'],
            'left'   => ['env(safe-area-inset-left)'],
            'right'  => ['env(safe-area-inset-right)'],
        ];
    }

    #[Test]
    #[DataProvider('safeAreaAxesProvider')]
    public function mobile_touch_css_references_each_safe_area_axis(string $envExpr): void
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);
        $this->assertStringContainsString(
            $envExpr,
            $css,
            "mobile-touch.css must reference {$envExpr} so the corresponding edge is padded on iOS."
        );
    }

    /**
     * Surfaces that must receive AI-515 safe-area treatment. Each
     * selector is the project's canonical handle for that surface.
     */
    public static function paddedSurfacesProvider(): array
    {
        return [
            'admin topbar'         => ['body.fi-panel-admin .fi-topbar'],
            'checkout topbar'      => ['body.fi-panel-checkout .fi-topbar'],
            'admin page wrapper'   => ['body.fi-panel-admin .fi-page'],
            'admin sidebar nav'    => ['body.fi-panel-admin .fi-sidebar-nav'],
            'live edit toolbar'    => ['.live-edit-toolbar'],
            'mw-modal close-X'     => ['.mw-modal-close-x'],
        ];
    }

    #[Test]
    #[DataProvider('paddedSurfacesProvider')]
    public function mobile_touch_css_pads_each_critical_mobile_surface(string $selector): void
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);
        $this->assertStringContainsString(
            $selector,
            $css,
            "AI-515 must cover `{$selector}` so the surface respects iOS safe-area insets."
        );
    }

    #[Test]
    public function mobile_touch_css_uses_max_with_zero_fallback(): void
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);

        // The `max(0px, env(...))` idiom is what keeps the rules
        // inert on hardware that reports zero insets — without it
        // the inset value alone would still apply (which is fine
        // for env() but the project standardises on max() for
        // future-proofing against negative or non-px insets).
        $this->assertMatchesRegularExpression(
            '/max\(\s*0px\s*,\s*env\(\s*safe-area-inset-(top|bottom|left|right)\s*\)\s*\)/',
            $css,
            'AI-515 rules must use the `max(0px, env(safe-area-inset-*))` idiom for safe fallback behaviour.'
        );
    }

    #[Test]
    public function mobile_touch_css_does_not_use_deprecated_constant_syntax(): void
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);

        // `constant(safe-area-inset-*)` is the iOS 11.0–11.1 deprecated
        // spelling. Modern iOS uses `env()` exclusively. The codebase
        // already targets iOS 12+ via the rest of mobile-touch.css.
        $this->assertStringNotContainsString(
            'constant(safe-area-inset',
            $css,
            'AI-515 rules must use the modern env() syntax, not the deprecated constant() spelling.'
        );
    }
}
