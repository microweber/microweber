<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-174 / AI-233 + AI-234 (2026-05-10) — public footer links
 * + "Add to this page" card text overflow.
 *
 *   AI-233 (P2) — Footer text links measured 20-23px tall on every
 *                 public page (/, /shop, /cart, /checkout, /blog,
 *                 /contact). Floor `<a>` inside `.footer-background`
 *                 to min-height: 44px with inline-flex + vertical
 *                 padding so the tap area meets WCAG 2.5.5 / iOS
 *                 HIG 44 floor.
 *
 *   AI-234 (P3) — "Add to this page" card description text
 *                 overflowed the card container at 320px. Cycle-148
 *                 line-clamp:3 hid the overflow with ellipsis but
 *                 the user couldn't read enough to understand the
 *                 action. Fix: shorten the source description from
 *                 ~200 chars to ~95 chars (same UX intent, fits
 *                 the card at 230px text column).
 */
class Ai233Ai234FooterLinksAndAddPageCardContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_174_anchor(): void
    {
        $css = $this->read('public/templates/bootstrap/css/public-touch.css');
        $php = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        $this->assertMatchesRegularExpression('/[Cc]ycle-174/', $css,
            'public-touch.css MUST carry the cycle-174 anchor.');
        $this->assertStringContainsString('AI-233', $css,
            'public-touch.css MUST carry the AI-233 anchor.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-174/', $php,
            'AdminLiveEditPage.php MUST carry the cycle-174 anchor.');
        $this->assertStringContainsString('AI-234', $php,
            'AdminLiveEditPage.php MUST carry the AI-234 anchor.');
    }

    #[Test]
    public function ai_233_footer_links_floored_to_44(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');
        // Floor every <a> inside .footer-background — the Bootstrap
        // template's footer wrapper section.
        $this->assertMatchesRegularExpression(
            '/\.footer-background\s+a[\s\S]{0,400}min-height:\s*44px/m',
            $src,
            'public-touch.css MUST floor .footer-background a to '
            . 'min-height: 44px so footer text links meet the WCAG '
            . '2.5.5 / iOS HIG 44 floor (was 20-23px).'
        );
        $this->assertMatchesRegularExpression(
            '/\.footer-background\s+a[\s\S]{0,400}display:\s*inline-flex/m',
            $src,
            'public-touch.css MUST also use display: inline-flex so '
            . 'the tap area grows visually without breaking inline '
            . 'text flow.'
        );
        // mw-powered-by sub-links also covered (inline credit links
        // inside a sentence).
        $this->assertMatchesRegularExpression(
            '/\.footer-background\s+\.mw-powered-by\s+a[\s\S]{0,400}min-height:\s*44px/m',
            $src,
            'public-touch.css MUST floor the .mw-powered-by inline '
            . 'credit links too (Microweber + Create a website 20px).'
        );
    }

    #[Test]
    public function ai_234_add_page_card_description_shortened(): void
    {
        $php = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        // Old (overflow) text MUST be gone — use a distinctive
        // sub-string from the original copy.
        $this->assertStringNotContainsString(
            'To add text, headings, or modules to the page you are editing right now',
            $php,
            'AdminLiveEditPage.php MUST NOT carry the original 200-'
            . 'char description that overflowed the card container '
            . 'at 320px width (cycle-148 line-clamp:3 hid the '
            . 'overflow with ellipsis but the user could not read '
            . 'enough of the sentence to understand the action).'
        );
        // New (shorter) text MUST be present.
        $this->assertStringContainsString(
            'Close this picker, then tap Insert layout in the toolbar',
            $php,
            'AdminLiveEditPage.php MUST carry the shortened AI-234 '
            . 'description that fits the card at 230px text column.'
        );
        // Keep pointing at left-rail drag affordance (same UX
        // intent as the original copy).
        $this->assertStringContainsString(
            'drag a block from the left rail',
            $php,
            'AdminLiveEditPage.php MUST keep the "drag a block from '
            . 'the left rail" affordance pointer in the new copy — '
            . 'same UX intent, just shorter.'
        );
    }

    #[Test]
    public function cycle_174_inside_touch_media_query(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');

        $anchorPos = strpos($src, 'cycle-174');
        $this->assertNotFalse($anchorPos, 'cycle-174 anchor must be present.');
        // Walk back to find the most recent enclosing @media block.
        $before = substr($src, 0, $anchorPos);
        $mediaPos = strrpos($before, '@media');
        $this->assertNotFalse($mediaPos, 'cycle-174 rules MUST sit inside an @media block.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*768px/',
            $mediaLine,
            'cycle-174 @media MUST include max-width: 768px (public '
            . 'mobile breakpoint, same as the cycle-N public-touch '
            . 'rules above).'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-174 @media MUST include (pointer: coarse) so real '
            . 'touch devices hit the floor regardless of width.');
    }
}
