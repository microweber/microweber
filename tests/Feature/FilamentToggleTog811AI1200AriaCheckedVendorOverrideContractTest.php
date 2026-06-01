<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-tog811 — AI-1200: Filament v5 native Toggle aria-checked initial-state gap.
 *
 * Pre-fix state probed via Playwright at /admin/pictures-module-settings:
 *   Stock vendor x-filament::toggle (vendor/filament/support/.../toggle.blade.php:16)
 *   binds aria-checked via x-bind:aria-checked="state?.toString()". When the entangled
 *   state ($wire.$entangle('options.X')) resolves undefined (record has no saved value),
 *   state?.toString() returns undefined and Alpine writes EMPTY STRING to aria-checked.
 *   ARIA 1.2 requires role="switch" carry aria-checked="true|false|mixed" — empty
 *   string is invalid, AT cannot announce the switch's state. WCAG 4.1.2 Level A.
 *
 * Fix: project-side vendor override at resources/views/vendor/filament/components/
 *      toggle.blade.php (note namespace is 'filament', not 'filament-support', per the
 *      AI-1199 lesson — Spatie PackageServiceProvider->name('filament') in
 *      vendor/filament/support/src/SupportServiceProvider.php:51).
 *
 *   x-bind:aria-checked="state ? 'true' : 'false'"
 *
 *   Coerces falsy (undefined/null/false) to literal 'false' string, truthy to literal
 *   'true' string — eliminates the undefined?.toString() empty-write.
 *
 * Carries to every Filament v5 Toggle surface project-wide.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade comments
 * before negative regression assertions so docblock prose mentioning the legacy
 * state-toString pattern cannot false-fail.
 */
class FilamentToggleTog811AI1200AriaCheckedVendorOverrideContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'resources/views/vendor/filament/components/toggle.blade.php'
        ));
        $this->bladeStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
    }

    #[Test]
    public function override_lives_at_the_correct_filament_namespace_path(): void
    {
        $this->assertFileExists(
            base_path('resources/views/vendor/filament/components/toggle.blade.php'),
            'Vendor override MUST live at resources/views/vendor/filament/components/toggle.blade.php — Spatie PackageServiceProvider->name(\'filament\') in vendor/filament/support/src/SupportServiceProvider.php registers the view namespace as \'filament\', so an override at vendor/filament-support/ silently does not load'
        );

        $this->assertFileDoesNotExist(
            base_path('resources/views/vendor/filament-support/components/toggle.blade.php'),
            'Wrong-namespace override at vendor/filament-support/ must not exist — it would be a no-op and mislead future audits'
        );
    }

    #[Test]
    public function override_binds_aria_checked_via_falsy_coerce_ternary(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-bind:aria-checked="state\s*\?\s*\'true\'\s*:\s*\'false\'"/s',
            $this->blade,
            'Override MUST bind x-bind:aria-checked via the falsy-coerce ternary so undefined/null state renders the literal "false" string (ARIA 1.2 requirement for role="switch")'
        );
    }

    #[Test]
    public function override_preserves_role_switch_and_type_button_merges(): void
    {
        $this->assertMatchesRegularExpression(
            '/->merge\(\[[^\]]*?\'role\'\s*=>\s*\'switch\'/s',
            $this->blade,
            '$attributes->merge() MUST keep role => switch so the button reports the switch role'
        );

        $this->assertMatchesRegularExpression(
            '/->merge\(\[[^\]]*?\'type\'\s*=>\s*\'button\'/s',
            $this->blade,
            '$attributes->merge() MUST keep type => button'
        );
    }

    #[Test]
    public function override_preserves_alpine_x_data_state_initializer(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-data="\{\s*state:\s*\{\{\s*\$state\s*\}\}\s*\}"/s',
            $this->blade,
            'x-data MUST keep the { state: {{ $state }} } initializer so $wire.$entangle still wires up'
        );
    }

    #[Test]
    public function override_preserves_x_on_click_toggle_handler(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-on:click="state\s*=\s*!\s*state"/s',
            $this->blade,
            'x-on:click MUST keep the state-flip handler so toggling still works'
        );
    }

    #[Test]
    public function override_preserves_x_bind_class_state_branch(): void
    {
        $this->assertStringContainsString(
            'x-bind:class=',
            $this->blade,
            'x-bind:class MUST stay — visual state classes depend on it'
        );

        $this->assertStringContainsString(
            'fi-toggle-on',
            $this->blade,
            'fi-toggle-on class MUST be reachable via the truthy branch'
        );

        $this->assertStringContainsString(
            'fi-toggle-off',
            $this->blade,
            'fi-toggle-off class MUST be reachable via the falsy branch'
        );
    }

    #[Test]
    public function legacy_state_toString_aria_checked_pattern_absent(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/x-bind:aria-checked="state\?\.toString\(\)"/s',
            $this->bladeStripped,
            'Legacy stock-vendor pattern x-bind:aria-checked="state?.toString()" (which writes empty string when state is undefined) MUST NOT return — WCAG 4.1.2 Level A regression guard'
        );
    }

    #[Test]
    public function task_markers_present_in_blade(): void
    {
        $this->assertStringContainsString(
            'AI-1200',
            $this->blade,
            'Override MUST carry the AI-1200 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-tog811',
            $this->blade,
            'Override MUST carry the task-2026-05-31-tog811 marker for grep-ability'
        );
    }
}
