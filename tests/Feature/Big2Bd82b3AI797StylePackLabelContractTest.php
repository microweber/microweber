<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-bd82b3 / AI-797 — drop redundant " Template" suffix
 * from 4 Big2 style-pack labels.
 * Jira: https://microweber.atlassian.net/browse/AI-797
 *
 * Big2-side data file commit: ec39816 (separate git subrepo;
 * Templates/Big2/ is its own repo per the established cadence).
 * Microweber-side commit: this contract test (source-pin of the
 * 4 expected labels at the JSON path).
 *
 * Pre-fix the admin Template dropdown displayed redundant suffixes
 * ("Home Restoration Template", "Human Resources Template", "Web
 * Designer Template", "FitPower Template"). Drop the suffix to reduce
 * label noise; the dropdown's own context already says "Template" so
 * each row repeating it is dead-weight chrome. "FitPower" kept as a
 * one-word brand per Q2 ACK (NOT renamed to "Fit Power").
 *
 * Bio Links rename out of scope — different fix shape (PHP array at
 * src/MicroweberPackages/LiveEdit/Http/Controllers/SetupWizardController.php
 * :35); folded as AI-829 footnote. AI-829 catalogue-wide sweep
 * (22 packs + Bio Links footnote + Art naming) follows AI-797 with
 * the same shape, larger batch.
 *
 * JSON path: $.settings[0].fieldSettings.styleProperties[0].label
 * Files under Templates/Big2/resources/assets/design-styles/style-packs/full-styles-properties/
 *
 * Note: Templates/Big2/ is gitignored from the microweber repo (separate
 * git subrepo); markTestSkipped on fresh-clone CI where Big2 isn't
 * checked out. The Big2 subrepo carries the canonical truth.
 */
class Big2Bd82b3AI797StylePackLabelContractTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function ai797LabelProvider(): array
    {
        return [
            'home-restoration' => [
                'Templates/Big2/resources/assets/design-styles/style-packs/full-styles-properties/00-home-restoration-template.json',
                'Home Restoration',
                'Home Restoration Template',
            ],
            'human-resources' => [
                'Templates/Big2/resources/assets/design-styles/style-packs/full-styles-properties/00-human-resources-template.json',
                'Human Resources',
                'Human Resources Template',
            ],
            'web-designer' => [
                'Templates/Big2/resources/assets/design-styles/style-packs/full-styles-properties/00-web-designer-template.json',
                'Web Designer',
                'Web Designer Template',
            ],
            'fitpower' => [
                'Templates/Big2/resources/assets/design-styles/style-packs/full-styles-properties/00-fitpower-template.json',
                'FitPower',
                'FitPower Template',
            ],
        ];
    }

    private function read(string $relativePath): ?string
    {
        $abs = base_path($relativePath);
        if (! file_exists($abs)) {
            return null;
        }
        return (string) file_get_contents($abs);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — positive guard: each file's styleProperties[0].label
    //          carries the new short label
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('ai797LabelProvider')]
    public function style_pack_label_matches_new_short_label(string $relativePath, string $newLabel, string $oldLabel): void
    {
        $source = $this->read($relativePath);
        if ($source === null) {
            $this->markTestSkipped("Big2 subrepo file {$relativePath} not present (Templates/Big2/ is gitignored from microweber; fresh-clone CI doesn't carry it).");
        }

        $decoded = json_decode($source, true);
        $this->assertIsArray($decoded, "AI-797: {$relativePath} must be valid JSON.");

        // Pin the shape: $.settings[0].fieldSettings.styleProperties[0].label
        $label = $decoded['settings'][0]['fieldSettings']['styleProperties'][0]['label'] ?? null;
        $this->assertSame(
            $newLabel,
            $label,
            "AI-797: {$relativePath} must carry label '{$newLabel}' at JSON path \$.settings[0].fieldSettings.styleProperties[0].label."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — negative regression guard: the old " Template" suffix is
    //          gone from the label position (not just the file as a whole;
    //          internal properties can legitimately mention "template")
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('ai797LabelProvider')]
    public function legacy_template_suffix_gone_from_label(string $relativePath, string $newLabel, string $oldLabel): void
    {
        $source = $this->read($relativePath);
        if ($source === null) {
            $this->markTestSkipped("Big2 subrepo file {$relativePath} not present (fresh-clone CI).");
        }

        $decoded = json_decode($source, true);
        $this->assertIsArray($decoded);

        $label = $decoded['settings'][0]['fieldSettings']['styleProperties'][0]['label'] ?? null;

        // The label MUST NOT carry the legacy suffixed form.
        $this->assertNotSame(
            $oldLabel,
            $label,
            "AI-797 regression guard: {$relativePath} MUST NOT carry the legacy label '{$oldLabel}' at the styleProperties[0].label JSON path."
        );

        // For the 3 packs whose old form ended in " Template", the new
        // label must NOT end in " Template" either. (FitPower's "old"
        // form ends in " Template"; the new "FitPower" doesn't. So
        // the assertion holds across all 4.)
        $this->assertDoesNotMatchRegularExpression(
            '/\sTemplate$/',
            (string) $label,
            "AI-797 regression guard: {$relativePath} label MUST NOT end with ' Template' suffix."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — schema-drift selector-self-match guard: assert the JSON
    //          path itself is intact (settings → fieldSettings →
    //          styleProperties → label) so future schema reshapes don't
    //          silently break this contract
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('ai797LabelProvider')]
    public function json_path_schema_intact(string $relativePath, string $newLabel, string $oldLabel): void
    {
        $source = $this->read($relativePath);
        if ($source === null) {
            $this->markTestSkipped("Big2 subrepo file {$relativePath} not present (fresh-clone CI).");
        }

        $decoded = json_decode($source, true);
        $this->assertIsArray($decoded);

        $this->assertArrayHasKey('settings', $decoded, "AI-797 schema: {$relativePath} must carry top-level `settings` array.");
        $this->assertArrayHasKey(0, $decoded['settings'], "AI-797 schema: settings[0] must exist.");
        $this->assertArrayHasKey('fieldSettings', $decoded['settings'][0], "AI-797 schema: settings[0].fieldSettings must exist.");
        $this->assertArrayHasKey('styleProperties', $decoded['settings'][0]['fieldSettings'], "AI-797 schema: settings[0].fieldSettings.styleProperties must exist.");
        $this->assertArrayHasKey(0, $decoded['settings'][0]['fieldSettings']['styleProperties'], "AI-797 schema: styleProperties[0] must exist.");
        $this->assertArrayHasKey('label', $decoded['settings'][0]['fieldSettings']['styleProperties'][0], "AI-797 schema: styleProperties[0].label must exist.");
    }
}
