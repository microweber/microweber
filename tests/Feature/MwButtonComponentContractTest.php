<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-109 / AI-118 / TICKET-CH — MwButton Vue component regression
 * coverage.
 *
 * Pins:
 *   - The component file exists at the canonical path called out by
 *     the brief.
 *   - Required props (`tabindex`, `disabled`, `loading`, `variant`,
 *     `size`, `action`) are declared.
 *   - aria-disabled is bound to the `disabled` prop AND the click
 *     handler short-circuits when disabled (the brief's "no inline
 *     onclick" line item is enforced via @click on the template).
 *   - The base `.mw-button` rule enforces `min-width: 44px` AND
 *     `min-height: 44px` per WCAG 2.5.5 / iOS HIG.
 *   - One demo migration site exists (FilePicker.vue) so future
 *     callers can see the import pattern.
 *
 * Style after the cycle-52..108 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class MwButtonComponentContractTest extends TestCase
{
    private const COMPONENT = 'packages/frontend-assets/resources/assets/ui/components/MwButton.vue';
    private const DEMO_SITE = 'packages/frontend-assets/resources/assets/ui/components/Form/FilePicker.vue';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function component_lives_at_canonical_path(): void
    {
        $this->assertFileExists(
            base_path(self::COMPONENT),
            self::COMPONENT . ' must exist (brief says `packages/frontend-assets/resources/assets/ui/components/MwButton.vue`)'
        );
    }

    #[Test]
    public function component_declares_required_props(): void
    {
        $src = $this->read(self::COMPONENT);

        // Each prop the brief explicitly called out.
        foreach ([
            'variant',
            'size',
            'disabled',
            'loading',
            'tabindex',
            'action',
        ] as $prop) {
            $this->assertMatchesRegularExpression(
                "/{$prop}:\\s*\\{/",
                $src,
                "MwButton.vue: must declare prop `{$prop}` with a definition object"
            );
        }
    }

    #[Test]
    public function aria_disabled_and_click_short_circuit_are_wired(): void
    {
        $src = $this->read(self::COMPONENT);

        // aria-disabled binding from disabled prop.
        $this->assertStringContainsString(
            ":aria-disabled=\"disabled ? 'true' : null\"",
            $src,
            'MwButton.vue: must bind :aria-disabled to the disabled prop ("true" / null)'
        );

        // tabindex flips to -1 when disabled (skips tab order while
        // still allowing programmatic focus).
        $this->assertStringContainsString(
            ':tabindex="disabled ? -1 : tabindex"',
            $src,
            'MwButton.vue: must flip tabindex to -1 when disabled'
        );

        // onClick short-circuits on disabled / loading.
        $this->assertMatchesRegularExpression(
            '/if\\s*\\(\\s*this\\.disabled\\s*\\|\\|\\s*this\\.loading\\s*\\)\\s*\\{[\\s\\S]{0,200}event\\.preventDefault\\(\\);\\s*event\\.stopPropagation\\(\\);\\s*return;/',
            $src,
            'MwButton.vue: onClick must short-circuit (preventDefault + stopPropagation + return) when disabled OR loading'
        );

        // No inline onclick attribute in the template.
        $this->assertStringNotContainsString(
            ' onclick="',
            $src,
            'MwButton.vue: must NOT use inline onclick="..." (CSP-clean)'
        );
    }

    #[Test]
    public function base_class_enforces_44x44_minimum_touch_target(): void
    {
        $src = $this->read(self::COMPONENT);

        // The brief's WCAG 2.5.5 / iOS HIG minimum.
        $this->assertMatchesRegularExpression(
            '/\\.mw-button\\s*\\{[^}]*min-width:\\s*44px[^}]*min-height:\\s*44px/s',
            $src,
            'MwButton.vue: .mw-button base class must declare both min-width: 44px AND min-height: 44px'
        );
    }

    #[Test]
    public function demo_migration_site_imports_and_uses_mw_button(): void
    {
        $src = $this->read(self::DEMO_SITE);

        $this->assertStringContainsString(
            "import MwButton from '../MwButton.vue'",
            $src,
            self::DEMO_SITE . ': must import MwButton.vue (one demo migration site demonstrating the import pattern)'
        );
        $this->assertStringContainsString(
            'components: { MwButton }',
            $src,
            self::DEMO_SITE . ': must register MwButton in the components option'
        );
        $this->assertMatchesRegularExpression(
            '/<MwButton[\\s\\S]{0,400}aria-label/',
            $src,
            self::DEMO_SITE . ': must demonstrate <MwButton> usage with an aria-label'
        );
    }
}
