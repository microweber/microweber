<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-08-eseliteral — ESE section (accordion) styling must be LITERAL
 * CSS, not Tailwind @apply.
 *
 * The `.element-style-editor-toggle-wrapper` section styling had been written
 * with Tailwind @apply directives. @apply only renders if the Webpack/Tailwind
 * build expands it, so a stale or differently-built theme bundle left every ESE
 * section completely UNSTYLED — bare text rows with no icons, spacing, borders,
 * hover/active states or chevrons (PM report). Rewritten with literal CSS (+
 * theme-aware --ese-* tokens with literal fallbacks) so the styling always
 * applies regardless of the @apply build step. This pins that the rule stays
 * literal in BOTH the source and the served bundle (Tier-2).
 */
class EseLiteralSectionStylingContractTest extends TestCase
{
    private const SOURCE = 'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css';
    private const BUNDLE = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    /**
     * Slice the `.element-style-editor-toggle-wrapper {` base rule body.
     */
    private function baseRule(string $relative): string
    {
        $css = (string) file_get_contents(base_path($relative));
        $start = strpos($css, '.element-style-editor-toggle-wrapper {');
        $this->assertNotFalse($start, "{$relative} must define .element-style-editor-toggle-wrapper.");
        $open = strpos($css, '{', $start);
        $close = strpos($css, '}', $open);
        return substr($css, $open, $close - $open);
    }

    private function assertLiteralStyling(string $relative): void
    {
        $rule = $this->baseRule($relative);

        // No raw @apply may survive in the section rule (the regression cause).
        $this->assertStringNotContainsString(
            '@apply',
            $rule,
            "{$relative}: the ESE section rule must NOT use @apply — it leaves sections unstyled when the bundle isn't Tailwind-expanded."
        );
        // Key literal declarations that give the section its shape.
        $this->assertMatchesRegularExpression('/cursor:\s*pointer/', $rule, "{$relative}: section must keep cursor:pointer.");
        $this->assertMatchesRegularExpression('/border-left:\s*2px\s+solid/', $rule, "{$relative}: section must keep its literal left border.");
        $this->assertMatchesRegularExpression('/padding:\s*6px\s+8px\s+6px\s+12px/', $rule, "{$relative}: section must keep its literal padding.");
    }

    #[Test]
    public function source_section_rule_is_literal_css(): void
    {
        $this->assertLiteralStyling(self::SOURCE);
    }

    #[Test]
    public function served_bundle_section_rule_is_literal_css(): void
    {
        // Tier-2 served-bundle guard — the rebuilt theme must carry the literal
        // rule, so a stale bundle can't strip the section styling again.
        $this->assertLiteralStyling(self::BUNDLE);
    }

    #[Test]
    public function active_section_uses_ese_accent_token_with_fallback(): void
    {
        $css = (string) file_get_contents(base_path(self::SOURCE));
        $this->assertMatchesRegularExpression(
            '/\.element-style-editor-toggle-wrapper\.active\s*\{[^}]*border-left:\s*3px\s+solid\s+var\(--ese-accent,\s*#0d6efd\)/s',
            $css,
            'Active section must use the --ese-accent token (with literal #0d6efd fallback) for its left border.'
        );
    }
}
