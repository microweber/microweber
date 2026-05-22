<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-af62dd / AI-303 MEDIUM — CookieNotice toggle row touch target.
 *
 * Tester measured the cookie toggle at 48×24px at 390×844 — height 24px is
 * below the WCAG 2.5.5 44×44px floor.
 *
 * The .switch element (48×24px) is the visual toggle track. The .cookie-option
 * row wraps the switch + category label text. Pattern: enlarging the row wrapper
 * preserves the visual toggle size while extending the tappable area — same
 * pattern as Filament checkbox (AI-517/AI-518) where the label row gets min-height.
 *
 * Current template status: the default CookieNotice Blade template
 * (Modules/CookieNotice/resources/views/templates/default.blade.php) does NOT
 * currently render the .switch / .cookie-option HTML — the template only shows
 * the Close button / Learn more link / Accept All button (all already at 44px
 * per cookie-notice.css AI-551 rules). This rule is defensive for when a
 * cookie preferences panel with per-category toggles is added. The CSS class
 * structure (.cookie-option, .switch, .slider) is already defined in
 * public/modules/cookie_notice/css/cookie-notice.css.
 */
class CookieNoticeAf62ddAI303ToggleTouchTargetContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $raw = (string) file_get_contents(
            base_path('Templates/Bootstrap/resources/assets/css/public-touch.css')
        );
        $this->src = $raw;
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;
    }

    #[Test]
    public function cookie_option_row_has_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.cookie-notice-wrapper\s+\.cookie-option\s*\{[^}]*min-height:\s*44px~s',
            $this->srcStripped,
            '.cookie-notice-wrapper .cookie-option must have min-height: 44px.'
        );
    }

    #[Test]
    public function cookie_option_row_uses_flex(): void
    {
        $pos = strrpos($this->srcStripped, '.cookie-notice-wrapper .cookie-option');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 150);
        $this->assertStringContainsString('display: flex', $slice,
            '.cookie-option must use display: flex for vertical centering.'
        );
        $this->assertStringContainsString('align-items: center', $slice,
            '.cookie-option must use align-items: center.'
        );
    }

    #[Test]
    public function rule_is_inside_touch_media_query(): void
    {
        $pos = strrpos($this->srcStripped, '.cookie-notice-wrapper .cookie-option');
        $this->assertNotFalse($pos);
        $before = substr($this->srcStripped, 0, (int) $pos);
        $mediaPos = strrpos($before, '@media');
        $this->assertNotFalse($mediaPos);
        $mediaSlice = substr($this->srcStripped, (int) $mediaPos, 60);
        $this->assertStringContainsString('1023.98px', $mediaSlice,
            'Rule must be inside the standard touch-viewport @media block.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-af62dd', $this->src);
    }

    #[Test]
    public function defensive_rule_note_present(): void
    {
        // The docblock must note this is defensive (template doesn't currently render toggles)
        $this->assertStringContainsString('defensive', $this->src);
    }

    #[Test]
    public function existing_close_button_still_at_44px(): void
    {
        // AI-551 regression guard — close button must stay at 44px in cookie-notice.css
        $cookieCssPath = base_path('public/modules/cookie_notice/css/cookie-notice.css');
        if (!file_exists($cookieCssPath)) {
            $this->markTestSkipped('cookie-notice.css not found.');
        }
        $css = (string) file_get_contents($cookieCssPath);
        $this->assertMatchesRegularExpression(
            '~\.close-button[^{]*\{[^}]*min-height:\s*44px~s',
            $css,
            'cookie-notice.css .close-button must still have min-height: 44px (AI-551).'
        );
    }

    #[Test]
    public function served_mirror_is_byte_identical(): void
    {
        $servedPath = base_path('public/templates/bootstrap/css/public-touch.css');
        if (!file_exists($servedPath)) {
            $this->markTestSkipped('Served mirror not present.');
        }
        $this->assertSame(
            md5($this->src),
            md5((string) file_get_contents($servedPath))
        );
    }
}
