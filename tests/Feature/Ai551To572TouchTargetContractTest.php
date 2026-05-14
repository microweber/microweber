<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-551 / AI-553 / AI-556 / AI-564 / AI-565 / AI-572 — Public-surface
 * touch-target floors (≥44×44 px, WCAG 2.5.5).
 *
 * Agent-test mobile audit results (2026-05-14, viewport 390×844 iPhone 13):
 *   AI-498  Content  .button-8 "Read more" links  ~36px high
 *   AI-499  Page     .mw-more  "Read More" links  ~36px high
 *   AI-551  CookieNotice  .close-button / .accept-all-button / .cookie-policy-link  <44px
 *   AI-553  Currency  toggle button (py-2) ~38px high
 *   AI-556  Multilanguage  .modern-lang-btn / .modern-lang-link  inline CSS 36→32px mobile
 *   AI-564  LayoutContent  .btn call-to-action links  no floor
 *   AI-565  Btn module  .btn-sm can be ~32px
 *   AI-572  Sharer  icon <a> wrappers around SVGs  ~24px
 *
 * All public-surface rules (AI-498/499/553/564/565/572) live in
 *   Templates/Bootstrap/resources/assets/css/public-touch.css
 * CookieNotice rules (AI-551) live in
 *   Modules/CookieNotice/resources/assets/css/cookie-notice.css
 * Multilanguage inline CSS (AI-556) is in the template directly.
 *
 * File-system reads only (no DB / Filament boot).
 *
 * Boundary guard: none of these rules should introduce selectors scoped
 * to body.fi-panel-admin or body.fi-panel-checkout — public-touch.css is
 * served on public templates, not inside Filament panels.
 */
class Ai551To572TouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const COOKIE_CSS       = 'Modules/CookieNotice/resources/assets/css/cookie-notice.css';
    private const MULTI_TMPL       = 'Modules/Multilanguage/resources/views/templates/default.blade.php';

    private string $publicTouch;
    private string $cookieCss;
    private string $multiTmpl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->publicTouch = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
        $this->cookieCss   = file_get_contents(base_path(self::COOKIE_CSS));
        $this->multiTmpl   = file_get_contents(base_path(self::MULTI_TMPL));
    }

    // -----------------------------------------------------------------------
    // AI-498 Content .button-8
    // -----------------------------------------------------------------------

    #[Test]
    public function ai498_content_button8_has_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.button-8\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->publicTouch,
            'AI-498: public-touch.css must set min-height:44px on .button-8'
        );
    }

    // -----------------------------------------------------------------------
    // AI-499 Page .mw-more
    // -----------------------------------------------------------------------

    #[Test]
    public function ai499_page_mw_more_has_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-more\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->publicTouch,
            'AI-499: public-touch.css must set min-height:44px on .mw-more'
        );
    }

    // -----------------------------------------------------------------------
    // AI-551 CookieNotice close / accept / learn-more
    // -----------------------------------------------------------------------

    #[Test]
    public function ai551_cookie_close_button_44_by_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.close-button\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->cookieCss,
            'AI-551: cookie-notice.css must set min-height:44px on .close-button'
        );
        $this->assertMatchesRegularExpression(
            '/\.close-button\s*\{[^}]*min-width\s*:\s*44px/s',
            $this->cookieCss,
            'AI-551: cookie-notice.css must set min-width:44px on .close-button'
        );
    }

    #[Test]
    public function ai551_cookie_accept_all_button_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.accept-all-button\b[^}]*min-height\s*:\s*44px/s',
            $this->cookieCss,
            'AI-551: cookie-notice.css must set min-height:44px on .accept-all-button'
        );
    }

    #[Test]
    public function ai551_cookie_policy_link_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.cookie-policy-link\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->cookieCss,
            'AI-551: cookie-notice.css must set min-height:44px on .cookie-policy-link'
        );
    }

    // -----------------------------------------------------------------------
    // AI-553 Currency switcher button
    // -----------------------------------------------------------------------

    #[Test]
    public function ai553_currency_switcher_button_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.currency-switcher\s+button\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->publicTouch,
            'AI-553: public-touch.css must set min-height:44px on .currency-switcher button'
        );
    }

    // -----------------------------------------------------------------------
    // AI-556 Multilanguage inline CSS — no 36/32px values remain
    // -----------------------------------------------------------------------

    #[Test]
    public function ai556_multilanguage_modern_lang_btn_is_44px(): void
    {
        // Only check the inline style block (not external content), so look for
        // both the property and that it's ≥44 rather than the old 36.
        $this->assertMatchesRegularExpression(
            '/\.modern-lang-btn\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->multiTmpl,
            'AI-556: Multilanguage template must set min-height:44px on .modern-lang-btn'
        );
    }

    #[Test]
    public function ai556_multilanguage_modern_lang_link_is_44px_desktop(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modern-lang-link\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->multiTmpl,
            'AI-556: Multilanguage template must set min-height:44px on .modern-lang-link (desktop)'
        );
    }

    #[Test]
    public function ai556_multilanguage_mobile_query_does_not_shrink_below_44(): void
    {
        // Inside the @media (max-width: 576px) block there should be no
        // min-height value less than 44px for modern-lang-* selectors.
        $this->assertStringNotContainsString(
            'min-height: 32px',
            $this->multiTmpl,
            'AI-556: Multilanguage mobile @media must not reduce touch target below 44px'
        );
        $this->assertStringNotContainsString(
            'min-height: 36px',
            $this->multiTmpl,
            'AI-556: Multilanguage template must not contain the old 36px min-height value'
        );
    }

    // -----------------------------------------------------------------------
    // AI-564 LayoutContent .btn
    // -----------------------------------------------------------------------

    #[Test]
    public function ai564_layout_content_btn_has_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.module-layout-content\s+\.btn\s*[,{][^}]*min-height\s*:\s*44px/s',
            $this->publicTouch,
            'AI-564: public-touch.css must set min-height:44px on .module-layout-content .btn'
        );
    }

    // -----------------------------------------------------------------------
    // AI-565 Btn .btn-sm
    // -----------------------------------------------------------------------

    #[Test]
    public function ai565_mw_btn_align_wrap_btn_sm_has_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-btn-align-wrap\s+\.btn-sm\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->publicTouch,
            'AI-565: public-touch.css must set min-height:44px on .mw-btn-align-wrap .btn-sm'
        );
    }

    // -----------------------------------------------------------------------
    // AI-572 Sharer social-share icon anchors
    // -----------------------------------------------------------------------

    #[Test]
    public function ai572_sharer_icon_link_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-social-share-links\s+a\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->publicTouch,
            'AI-572: public-touch.css must set min-height:44px on .mw-social-share-links a'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-social-share-links\s+a\s*\{[^}]*min-width\s*:\s*44px/s',
            $this->publicTouch,
            'AI-572: public-touch.css must set min-width:44px on .mw-social-share-links a'
        );
    }

    // -----------------------------------------------------------------------
    // AI-501 Menu module default skin nav links
    // -----------------------------------------------------------------------

    #[Test]
    public function ai501_menu_navigation_default_anchor_has_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.module-navigation-default\s+a\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->publicTouch,
            'AI-501: public-touch.css must set min-height:44px on .module-navigation-default a'
        );
    }

    // -----------------------------------------------------------------------
    // Boundary guard: no Filament panel selectors in public-touch.css
    // -----------------------------------------------------------------------

    #[Test]
    public function public_touch_css_has_no_filament_panel_selectors(): void
    {
        $this->assertStringNotContainsString(
            'body.fi-panel-admin',
            $this->publicTouch,
            'public-touch.css must not contain body.fi-panel-admin selectors (boundary guard)'
        );
        $this->assertStringNotContainsString(
            'body.fi-panel-checkout',
            $this->publicTouch,
            'public-touch.css must not contain body.fi-panel-checkout selectors (boundary guard)'
        );
    }
}
