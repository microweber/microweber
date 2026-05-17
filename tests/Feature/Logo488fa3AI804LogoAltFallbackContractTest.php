<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-488fa3 / AI-804  logo alt-text empty-fallback fix.
 * Jira: https://microweber.atlassian.net/browse/AI-804
 *
 * Lineage:
 *   - AI-803 (task-2026-05-17-fa5dc3)  sibling on same template
 *     (logo visibility fix). AI-803/AI-804/AI-805 all touch the
 *     same Modules/Logo/.../default.blade.php.
 *
 * Pre-fix: `alt="{{ isset($text) ? $text : '' }}"` rendered an empty
 * alt when no module text override was set. WCAG 2.1 SC 1.1.1 Level
 * A violation -- screen-reader users heard nothing when their cursor
 * landed on the brand mark.
 *
 * Fix: three-tier fallback precedence on the alt attribute:
 *   1. $text (user-set module override)
 *   2. get_option('website_title', 'website') (configured brand)
 *   3. literal 'Home' (final safety net -- never empty)
 *
 * Designer dispatch suggested option_get() -- the canonical
 * Microweber helper is actually get_option(key, group), matching
 * the existing pattern at
 * src/MicroweberPackages/App/resources/views/email/simple.blade.php:348.
 *
 * Designer Tier-3 probe:
 *   const img = document.querySelector('.logo-module img');
 *   expect(img.alt.length).toBeGreaterThan(0);
 */
class Logo488fa3AI804LogoAltFallbackContractTest extends TestCase
{
    private string $template;

    protected function setUp(): void
    {
        parent::setUp();
        $this->template = (string) file_get_contents(base_path(
            'Modules/Logo/resources/views/templates/default.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  three-tier fallback chain present
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function alt_attribute_uses_three_tier_fallback(): void
    {
        // Slice the img tag to scope assertions; the docblock prose
        // mentions the legacy `isset($text) ? $text : ''` shape and
        // the new fallback shape, so pre-strip Blade {{-- ... --}}
        // comments before scanning the alt attribute. Recurring
        // selector-self-match guard family per LESSONS.
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $this->template);

        $this->assertMatchesRegularExpression(
            "/alt=\"\\{\\{\\s*isset\\(\\\$text\\)\\s*&&\\s*\\\$text\\s*!==\\s*''\\s*\\?\\s*\\\$text\\s*:\\s*\\(get_option\\(\\s*'website_title'\\s*,\\s*'website'\\s*\\)\\s*\\?:\\s*'Home'\\s*\\)\\s*\\}\\}\"/",
            $stripped,
            'Logo <img alt> must use three-tier fallback: $text -> get_option("website_title", "website") -> "Home". Exact shape per AI-804 dispatch.'
        );
    }

    #[Test]
    public function legacy_empty_alt_fallback_pattern_is_gone(): void
    {
        // Pre-fix: alt="{{ isset($text) ? $text : '' }}" -- the empty
        // string fallback is the WCAG defect. Pre-strip Blade comments
        // (docblock mentions the legacy shape).
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $this->template);

        $this->assertStringNotContainsString(
            "alt=\"{{ isset(\$text) ? \$text : '' }}\"",
            $stripped,
            'Legacy `alt="{{ isset($text) ? $text : \'\' }}"` empty fallback must be gone -- it was the WCAG 1.1.1 defect.'
        );
    }

    #[Test]
    public function alt_fallback_chain_uses_get_option_not_option_get(): void
    {
        // Designer dispatch suggested `option_get(...)`; the canonical
        // Microweber helper is `get_option(key, group)`. Pin the right
        // helper so future refactors don't accidentally swap to the
        // wrong name (option_get exists as a back-compat alias but
        // get_option is the documented + widely-used canonical).
        $this->assertStringContainsString(
            "get_option('website_title', 'website')",
            $this->template,
            'AI-804 fix must use Microweber\'s canonical `get_option(key, group)` helper (designer dispatch suggested option_get; this is the canonical name).'
        );
    }

    #[Test]
    public function final_safety_net_literal_home_present(): void
    {
        // The final safety net 'Home' ensures the alt is NEVER empty
        // -- even on fresh installs where no website_title has been
        // configured (get_option returns null on miss). Tier-3 probe
        // would still pass: 'Home'.length === 4 > 0.
        $this->assertMatchesRegularExpression(
            "/\\?:\\s*'Home'/",
            $this->template,
            'Final safety-net `?: \'Home\'` must be present so the alt attribute is never empty even on fresh installs with no website_title set.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  AI-803 fix preserved (regression guard)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai803_logo_visibility_fix_preserved(): void
    {
        // AI-803 (task-fa5dc3) shipped 2 hours ago on the same
        // template. Pin its key markers so this AI-804 edit didn't
        // accidentally regress them.
        $this->assertStringContainsString(
            '@media (max-width: 575px)',
            $this->template,
            'AI-803 narrow-viewport @media block must remain intact.'
        );
        $this->assertStringContainsString(
            'task-2026-05-17-fa5dc3',
            $this->template,
            'AI-803 task-id marker must remain in the docblock.'
        );
    }

    #[Test]
    public function template_dom_structure_preserved(): void
    {
        $this->assertStringContainsString(
            '<div class="logo-module">',
            $this->template,
            'Outer wrapper preserved.'
        );
        $this->assertStringContainsString(
            'class="logo-link"',
            $this->template,
            'Anchor class preserved.'
        );
        $this->assertStringContainsString(
            "@if(isset(\$logoimage)",
            $this->template,
            'Image conditional render branch preserved.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  markers + lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai804_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-488fa3', $this->template);
        $this->assertStringContainsString('AI-804', $this->template);
    }
}
