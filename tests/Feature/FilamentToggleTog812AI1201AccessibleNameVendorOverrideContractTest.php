<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-tog812 — AI-1201: Filament v5 native Toggle accessible-name gap.
 *
 * Pre-fix state probed via Playwright at /admin/social-links-module-settings:
 *   Every <button role="switch"> rendered by Filament's forms Toggle wrapper
 *   carried aria-label="" AND aria-labelledby="" — DOM-confirmed across all
 *   13 SocialLinks toggles. Filament's field-wrapper renders
 *   <label for="{{ $id }}" class="fi-fo-field-label"> sibling-to (not parent-of)
 *   the button, BUT because the button carries role="switch", ARIA name-
 *   computation discards the <label for=""> association — only aria-label,
 *   aria-labelledby, text content, and title count as accessible-name sources
 *   for role-overridden elements. AT users heard "switch, off" with no
 *   indication of which switch. WCAG 4.1.2 Level A regression.
 *
 * Sister-fix to AI-1200 — same Filament Toggle vendor-override surface
 * family. Lives at the filament-forms namespace path because
 * Spatie PackageServiceProvider->name('filament-forms') in
 * vendor/filament/forms/src/FormsServiceProvider.php:21 registers the view
 * namespace as 'filament-forms', NOT 'filament'.
 *
 * Fix: inject 'aria-label' => $getLabel() into the attribute bag passed to
 *      the <x-filament::toggle> support component. The AI-1200 override at
 *      resources/views/vendor/filament/components/toggle.blade.php emits the
 *      aria-label naturally via its $attributes->merge() bag.
 *
 * Verified post-fix via Playwright DOM probe on 5 sampled toggles:
 *   form.options.facebook_enabled  → aria-label="Enable Facebook Sharing"
 *   form.options.x_enabled         → aria-label="Enable X Sharing"
 *   form.options.pinterest_enabled → aria-label="Enable Pinterest Sharing"
 *   form.options.linkedin_enabled  → aria-label="Enable LinkedIn Sharing"
 *   form.options.viber_enabled     → aria-label="Enable Viber Sharing"
 *
 * Carries to every Filament v5 Toggle surface project-wide (Pictures
 * options.lightbox, Btn options.urlBlank, all 13 SocialLinks toggles,
 * every Toggle::make() in the codebase).
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade
 * comments before negative regression assertions so docblock prose mentioning
 * the legacy attribute-bag-without-aria-label pattern cannot false-fail.
 */
class FilamentToggleTog812AI1201AccessibleNameVendorOverrideContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'resources/views/vendor/filament-forms/components/toggle.blade.php'
        ));
        $this->bladeStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
    }

    #[Test]
    public function override_lives_at_the_correct_filament_forms_namespace_path(): void
    {
        $this->assertFileExists(
            base_path('resources/views/vendor/filament-forms/components/toggle.blade.php'),
            'Vendor override MUST live at resources/views/vendor/filament-forms/components/toggle.blade.php — Spatie PackageServiceProvider->name(\'filament-forms\') in vendor/filament/forms/src/FormsServiceProvider.php:21 registers the view namespace as \'filament-forms\', so an override at vendor/filament/components/ would target the support package instead and silently not load for the forms wrapper'
        );
    }

    #[Test]
    public function override_injects_aria_label_from_get_label_into_attribute_bag(): void
    {
        $this->assertMatchesRegularExpression(
            '/\'aria-label\'\s*=>\s*\$getLabel\(\)\s*,/s',
            $this->blade,
            'Override MUST inject \'aria-label\' => $getLabel() into the ->merge([...]) array so the rendered <button role="switch"> carries a non-empty accessible name (ARIA 1.2 requirement for role-overridden buttons)'
        );
    }

    #[Test]
    public function aria_label_is_dynamic_not_hardcoded_string(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/\'aria-label\'\s*=>\s*\'[^\']*\'\s*,/s',
            $this->bladeStripped,
            'aria-label MUST be wired to $getLabel() (dynamic per-Toggle::make() label), NOT a hardcoded string literal — otherwise every toggle in the project announces the same name'
        );
    }

    #[Test]
    public function override_preserves_upstream_attribute_bag_keys(): void
    {
        $keys = [
            'aria-checked',
            'autofocus',
            'disabled',
            'id',
            'offColor',
            'offIcon',
            'onColor',
            'onIcon',
            'state',
            'wire:loading.attr',
            'wire:target',
        ];

        foreach ($keys as $key) {
            $this->assertMatchesRegularExpression(
                '/\'' . preg_quote($key, '/') . '\'\s*=>/s',
                $this->blade,
                sprintf(
                    'Override MUST preserve the upstream \'%s\' key in the attribute bag — dropping it would break the Filament Toggle wiring',
                    $key
                )
            );
        }
    }

    #[Test]
    public function override_preserves_extra_attribute_merge_chain(): void
    {
        $this->assertStringContainsString(
            '$getExtraAttributes()',
            $this->blade,
            'Override MUST preserve the ->merge($getExtraAttributes(), escape: false) chain so Filament Toggle ->extraAttributes() callers still work'
        );

        $this->assertStringContainsString(
            '$getExtraAlpineAttributes()',
            $this->blade,
            'Override MUST preserve the ->merge($getExtraAlpineAttributes(), escape: false) chain so Filament Toggle ->extraAlpineAttributes() callers still work'
        );

        $this->assertStringContainsString(
            "->class(['fi-fo-toggle'])",
            $this->blade,
            'Override MUST preserve the ->class([\'fi-fo-toggle\']) call so existing Filament forms-toggle styles still apply'
        );
    }

    #[Test]
    public function override_preserves_state_path_entangle_wiring(): void
    {
        $this->assertStringContainsString(
            '$applyStateBindingModifiers',
            $this->blade,
            'Override MUST preserve $applyStateBindingModifiers() so $wire.$entangle() binding modifiers (debounce, defer, etc.) keep working'
        );

        $this->assertStringContainsString(
            '$entangle',
            $this->blade,
            'Override MUST preserve the $entangle() expression so Livewire state synchronisation still flows through Alpine'
        );
    }

    #[Test]
    public function override_preserves_isinline_branch_and_dynamic_field_wrapper(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$isInline\(\)\s*\)/s',
            $this->blade,
            'Override MUST preserve the @if ($isInline()) branch so inline-label Toggles render via x-slot name="labelPrefix"'
        );

        $this->assertStringContainsString(
            'name="labelPrefix"',
            $this->blade,
            'Override MUST preserve the <x-slot name="labelPrefix"> branch so inline Toggle labels stay anchored to the field-wrapper prefix'
        );

        $this->assertMatchesRegularExpression(
            '/<x-dynamic-component\s+:component="\$fieldWrapperView"/s',
            $this->blade,
            'Override MUST preserve the <x-dynamic-component :component="$fieldWrapperView" /> render so the Filament field-wrapper still wraps the toggle (label/helper/errors slots stay intact)'
        );
    }

    #[Test]
    public function override_renders_via_filament_support_toggle_with_prepared_attributes(): void
    {
        $this->assertMatchesRegularExpression(
            '/<x-filament::toggle\s+:attributes="\\\\Filament\\\\Support\\\\prepare_inherited_attributes\(\$attributes\)"/s',
            $this->blade,
            'Override MUST render <x-filament::toggle :attributes="\\Filament\\Support\\prepare_inherited_attributes($attributes)" /> so the AI-1200 support-component override (vendor/filament/components/toggle.blade.php) receives the attribute bag and renders the role="switch" button with the injected aria-label'
        );
    }

    #[Test]
    public function legacy_attribute_bag_without_aria_label_pattern_absent(): void
    {
        $mergeBlockMatched = preg_match(
            '/->merge\(\[(.*?)\],\s*escape:\s*false\)/s',
            $this->bladeStripped,
            $m
        );

        $this->assertSame(
            1,
            $mergeBlockMatched,
            'Override MUST contain a ->merge([...], escape: false) initial bag'
        );

        $this->assertStringContainsString(
            'aria-label',
            $m[1],
            'Legacy upstream attribute bag (which omits aria-label and thus leaves role="switch" buttons with no accessible name) MUST NOT return — WCAG 4.1.2 Level A regression guard'
        );
    }

    #[Test]
    public function task_markers_present_in_blade(): void
    {
        $this->assertStringContainsString(
            'AI-1201',
            $this->blade,
            'Override MUST carry the AI-1201 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-tog812',
            $this->blade,
            'Override MUST carry the task-2026-05-31-tog812 marker for grep-ability'
        );

        $this->assertStringContainsString(
            'AI-1200',
            $this->blade,
            'Override SHOULD cite AI-1200 in the docblock so the sister-fix lineage (support-component aria-checked override) is discoverable from this file'
        );
    }
}
