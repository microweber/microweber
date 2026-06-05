<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\CustomFields\Enums\CustomFieldTypes;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-c188de / AI-788 + AI-789 — R10-3 form-widget umbrella.
 * Jira:
 *   https://microweber.atlassian.net/browse/AI-788
 *   https://microweber.atlassian.net/browse/AI-789
 *
 * AI-788 — Custom Field wizard polish (Slice A).
 *   - Labels: hyphenation modernised ("E-mail" → "Email", "Web Site" →
 *     "Website"); redundant " Field" suffix dropped from labels (the
 *     wizard is already labelled "Field type"); capitalisation made
 *     consistent ("Single choice" / "Multiple choice" lowercase second
 *     word).
 *   - Descriptions: replaced the tautological "X field" copy with
 *     actionable one-line descriptions explaining WHEN to pick each
 *     field type (so the customer doesn't have to guess Text vs
 *     Property vs Hidden, or Dropdown vs Radio vs Checkbox).
 *   - New `getGroup(): string` method returns one of 7 group labels
 *     per case (Basic input / Choice / Commerce / Date & time /
 *     Location / Web & media / Utility). Enables future wizard UI
 *     restructure (AI-788b) to render grouped sections instead of
 *     flat 3-col grid; no UI change in this slice.
 *
 * AI-789 — Systemic admin empty-state partial.
 *   - New `mw-filament::partials.admin-empty-state` Blade partial
 *     formalises the AI-728/729/730 pattern as a reusable shape.
 *   - Required: $heading. Optional: $description, $cta_label,
 *     $cta_href, $cta_aria, $icon, $extra_class.
 *   - Companion CSS scopes .mw-admin-empty-state chrome (centered
 *     flex-column, vertical rhythm, ESE-token typography, dark-theme
 *     aware). Reuses the existing .mw-table-empty-cta button styling.
 *   - Consumers `@include` with named variables — single source of
 *     truth for the shape; per-resource copy still local. Existing
 *     per-model empty-state.blade.php may eventually migrate to
 *     consume this partial (separate refactor cycle).
 *
 * Deferred to AI-788b:
 *   - Wizard UI restructure (render the 7 groups visually instead
 *     of flat grid). Requires finding the actual wizard component.
 *   - Wizard search (no full-text search across labels yet).
 */
class AdminC188deAI788AI789FormWidgetUmbrellaContractTest extends TestCase
{
    private string $partial;
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->partial = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament-forms/partials/admin-empty-state.blade.php'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->bundle = file_exists(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AI-788 modernised labels (hyphenation + redundant-suffix drop)
    // ─────────────────────────────────────────────────────────────────────

    public static function modernisedLabels(): array
    {
        return [
            'Email (was E-mail)' => [CustomFieldTypes::EMAIL, 'Email'],
            'Website (was Web Site)' => [CustomFieldTypes::SITE, 'Website'],
            'Text (was Text Field)' => [CustomFieldTypes::TEXT, 'Text'],
            'File upload (was File Upload)' => [CustomFieldTypes::UPLOAD, 'File upload'],
            'Single choice (was Single Choice)' => [CustomFieldTypes::RADIO, 'Single choice'],
            'Multiple choice (was Multiple choices)' => [CustomFieldTypes::CHECKBOX, 'Multiple choice'],
            'Hidden (was Hidden Field)' => [CustomFieldTypes::HIDDEN, 'Hidden'],
            'Section break (was Break Line)' => [CustomFieldTypes::BREAKLINE, 'Section break'],
        ];
    }

    #[Test]
    #[DataProvider('modernisedLabels')]
    public function ai788_labels_are_modernised(CustomFieldTypes $case, string $expectedLabel): void
    {
        $this->assertSame(
            $expectedLabel,
            $case->getLabel(),
            sprintf('CustomFieldTypes::%s label must read %s', $case->name, $expectedLabel)
        );
    }

    #[Test]
    public function ai788_legacy_hyphenated_labels_are_gone(): void
    {
        // Source-side regression guard against the old hyphenated forms
        // creeping back in (strip comments first — docblock legitimately
        // mentions "E-mail" / "Web Site" as the BEFORE state).
        $source = (string) file_get_contents(base_path(
            'Modules/CustomFields/Enums/CustomFieldTypes.php'
        ));
        $stripped = preg_replace('!/\*.*?\*/!s', '', $source);
        $stripped = preg_replace('!//.*$!m', '', $stripped);
        $this->assertStringNotContainsString(
            "=> 'E-mail'",
            $stripped,
            "Legacy hyphenated 'E-mail' label must be gone."
        );
        $this->assertStringNotContainsString(
            "=> 'Web Site'",
            $stripped,
            "Legacy spaced 'Web Site' label must be gone."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — AI-788 actionable descriptions (no more tautological "X field")
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai788_descriptions_are_no_longer_tautological(): void
    {
        // Every description must NOT equal the case-name-plus-"field"
        // shape. Iterate every case + assert.
        foreach (CustomFieldTypes::cases() as $case) {
            $desc = $case->getDescriptions();
            $this->assertIsString($desc, "Description must be a string for {$case->name}.");
            // Tautological pattern: label + " field" / " Field"
            $label = $case->getLabel();
            $taut = $label . ' field';
            $this->assertNotEquals(
                strtolower($taut),
                strtolower($desc),
                "Description for {$case->name} must NOT be tautological ({$label} + 'field' = {$desc})."
            );
            // Minimum-information guard: description must be ≥10 chars
            // (forces something more substantive than just the word).
            $this->assertGreaterThanOrEqual(10, mb_strlen($desc), "Description for {$case->name} must be ≥10 chars; got '{$desc}'.");
        }
    }

    #[Test]
    public function ai788_specific_descriptions_landed(): void
    {
        // Spot-check 4 descriptions where the BEFORE→AFTER copy
        // contrast is most informative.
        $this->assertSame('Single line of free-form text', CustomFieldTypes::TEXT->getDescriptions());
        $this->assertSame('Pick one option from a closed list', CustomFieldTypes::DROPDOWN->getDescriptions());
        $this->assertSame('Validated email address', CustomFieldTypes::EMAIL->getDescriptions());
        $this->assertSame('Validated URL input', CustomFieldTypes::SITE->getDescriptions());
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-788 getGroup() metadata for future wizard restructure
    // ─────────────────────────────────────────────────────────────────────

    public static function groupAssignments(): array
    {
        return [
            'TEXT → Basic input' => [CustomFieldTypes::TEXT, 'Basic input'],
            'EMAIL → Basic input' => [CustomFieldTypes::EMAIL, 'Basic input'],
            'DROPDOWN → Choice' => [CustomFieldTypes::DROPDOWN, 'Choice'],
            'PRICE → Commerce' => [CustomFieldTypes::PRICE, 'Commerce'],
            'DATE → Date & time' => [CustomFieldTypes::DATE, 'Date & time'],
            'ADDRESS → Location' => [CustomFieldTypes::ADDRESS, 'Location'],
            'SITE → Web & media' => [CustomFieldTypes::SITE, 'Web & media'],
            'HIDDEN → Utility' => [CustomFieldTypes::HIDDEN, 'Utility'],
        ];
    }

    #[Test]
    #[DataProvider('groupAssignments')]
    public function ai788_get_group_returns_expected_label(CustomFieldTypes $case, string $expectedGroup): void
    {
        $this->assertSame(
            $expectedGroup,
            $case->getGroup(),
            sprintf('CustomFieldTypes::%s must belong to group %s', $case->name, $expectedGroup)
        );
    }

    #[Test]
    public function ai788_get_group_covers_every_case(): void
    {
        // Defensive: every enum case must have a group assignment so
        // the future wizard restructure can render every option in
        // SOME group. If a new case is added without a group, the
        // match() throws — this test catches it at PHPUnit time
        // before runtime would.
        foreach (CustomFieldTypes::cases() as $case) {
            $group = $case->getGroup();
            $this->assertIsString($group);
            $this->assertNotSame('', trim($group));
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — AI-789 admin-empty-state partial structure
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai789_partial_renders_heading_unconditionally(): void
    {
        $this->assertStringContainsString(
            '<h2 class="mw-admin-empty-state__heading">',
            $this->partial,
            'Partial must render heading h2 with .mw-admin-empty-state__heading class.'
        );
        $this->assertStringContainsString(
            '{{ $mwAdminEmptyHeading }}',
            $this->partial,
            'Partial must echo the $mwAdminEmptyHeading variable inside the h2.'
        );
    }

    #[Test]
    public function ai789_partial_gates_description_cta_and_icon_on_presence(): void
    {
        // Description renders only when $mwAdminEmptyDescription is set.
        $this->assertStringContainsString('@if ($mwAdminEmptyDescription)', $this->partial);
        $this->assertStringContainsString('mw-admin-empty-state__body', $this->partial);

        // CTA renders only when BOTH label AND href are set.
        $this->assertStringContainsString('@if ($mwAdminEmptyCtaLabel && $mwAdminEmptyCtaHref)', $this->partial);
        $this->assertStringContainsString('mw-table-empty-cta mw-admin-empty-state__cta', $this->partial);

        // Icon renders only when $mwAdminEmptyIcon is set.
        $this->assertStringContainsString('@if ($mwAdminEmptyIcon)', $this->partial);
        $this->assertStringContainsString('mw-admin-empty-state__icon', $this->partial);
    }

    #[Test]
    public function ai789_partial_aria_label_falls_back_to_cta_label(): void
    {
        // When $cta_aria is missing, the rendered aria-label uses
        // $cta_label so AT users always hear meaningful text.
        $this->assertStringContainsString(
            "\$mwAdminEmptyCtaAria      = \$cta_aria ?? \$mwAdminEmptyCtaLabel;",
            $this->partial,
            'Partial must default $mwAdminEmptyCtaAria to $mwAdminEmptyCtaLabel when $cta_aria is missing.'
        );
    }

    #[Test]
    public function ai789_partial_extra_class_passes_through_escaped(): void
    {
        // Defensive: any caller-supplied $extra_class must pass
        // through e() so an injection cannot escape the class
        // attribute.
        $this->assertStringContainsString(
            'class="mw-admin-empty-state {{ e($mwAdminEmptyExtraClass) }}"',
            $this->partial,
            'Partial must escape $mwAdminEmptyExtraClass via e() before interpolating into the class attribute.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — AI-789 companion CSS + bundle runtime probe
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_carries_admin_empty_state_chrome_rules(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-admin-empty-state\s*\{/',
            $this->css,
            'general-styles.css must scope .mw-admin-empty-state container rule to admin panel.'
        );
        $this->assertStringContainsString('.mw-admin-empty-state__heading', $this->css);
        $this->assertStringContainsString('.mw-admin-empty-state__body', $this->css);
        $this->assertStringContainsString('.mw-admin-empty-state__cta-wrap', $this->css);
        $this->assertStringContainsString('html.dark body.fi-panel-admin .mw-admin-empty-state__heading', $this->css);
    }

    #[Test]
    public function css_token_fallbacks_present_on_every_var_in_slice(): void
    {
        // SOUL #108 — every var() in the AI-789 slice must carry a
        // literal fallback. Slice from AI-789 docblock to next AI
        // marker / EOF.
        $start = strpos($this->css, 'AI-789 (task-2026-05-17-c188de)');
        $this->assertNotFalse($start);
        $docEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($docEnd);
        $sliceStart = $docEnd + 2;
        $sliceEnd = strpos($this->css, '/*', $sliceStart);
        $slice = $sliceEnd === false
            ? substr($this->css, $sliceStart)
            : substr($this->css, $sliceStart, $sliceEnd - $sliceStart);
        preg_match_all('/var\(([^)]+)\)/', $slice, $matches);
        foreach ($matches[1] as $varExpr) {
            $this->assertStringContainsString(
                ',',
                $varExpr,
                "Every var() in the AI-789 CSS slice must carry a literal fallback. Offender: `var({$varExpr})`."
            );
        }
        $this->assertGreaterThan(0, count($matches[1]), 'AI-789 slice must consume ESE tokens.');
    }

    #[Test]
    public function bundle_carries_admin_empty_state_class(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Served theme bundle absent.');
        }
        $this->assertStringContainsString('.mw-admin-empty-state', $this->bundle);
        $this->assertStringContainsString('.mw-admin-empty-state__heading', $this->bundle);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai_markers_present(): void
    {
        $enumSource = (string) file_get_contents(base_path(
            'Modules/CustomFields/Enums/CustomFieldTypes.php'
        ));
        $this->assertStringContainsString('task-2026-05-17-c188de', $enumSource);
        $this->assertStringContainsString('AI-788', $enumSource);
        $this->assertStringContainsString('task-2026-05-17-c188de', $this->partial);
        $this->assertStringContainsString('AI-789', $this->partial);
        $this->assertStringContainsString('task-2026-05-17-c188de', $this->css);
        $this->assertStringContainsString('AI-789', $this->css);
    }
}
