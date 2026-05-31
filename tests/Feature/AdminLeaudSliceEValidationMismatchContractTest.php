<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-leaud Slice E — Filament Select/numeric default-value
 * mismatches in two Live Edit ModuleSettings forms surfaced during
 * Phase 2A audit.
 *
 * In-scope (2 fixes):
 *   1. SearchSettings options.searchPosition Select — option keys are
 *      string slugs (start, center, end). Pre-fix default was integer 1
 *      which never matches an option, so the Select rendered with no
 *      preselection. Post-fix default is 'start' (Left).
 *   2. MarqueeModuleSettings options.animationSpeed TextInput is
 *      ->numeric() constrained. Pre-fix default was 'normal' which
 *      conflicted with the numeric validator. Post-fix default is 100
 *      matching MarqueeModule::getOption('animationSpeed', 100) and
 *      the runtime non-numeric normaliser at MarqueeModule.php:32-33.
 *
 * Out-of-scope (intentional dual-shape, NOT a defect):
 *   - ImageRolloverModuleSettings options.size TextInput default '350'.
 *     HelperText explicitly says "Enter image size in pixels or 'auto'"
 *     so the field accepts BOTH numeric strings AND the literal 'auto'.
 *     No ->numeric() constraint is declared. Originally flagged in the
 *     Phase 2A audit but ruled out on closer read.
 */
class AdminLeaudSliceEValidationMismatchContractTest extends TestCase
{
    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    #[Test]
    public function search_position_select_default_is_start_not_integer_one(): void
    {
        $source = $this->fileContents('Modules/Search/Filament/SearchSettings.php');
        $this->assertNotSame('', $source);

        $anchor = "Select::make('options.searchPosition')";
        $pos = strpos($source, $anchor);
        $this->assertNotFalse($pos, "Anchor not found: {$anchor}");

        // Fixed-length lookahead window per AI-816 lesson.
        $slice = substr($source, $pos, 800);

        $this->assertStringContainsString(
            "->default('start')",
            $slice,
            'searchPosition Select must default to the literal string key "start" matching the options array.'
        );

        // Selector-self-match guard per LESSONS UNIFORMITY-RULE.
        $sliceWithoutComments = preg_replace('~//[^\n]*~', '', $slice);
        $sliceWithoutComments = preg_replace('~/\*.*?\*/~s', '', (string) $sliceWithoutComments);

        $this->assertDoesNotMatchRegularExpression(
            '/->default\(\s*1\s*\)/',
            (string) $sliceWithoutComments,
            'Regression: searchPosition Select default reverted to integer 1 (never matches string option keys).'
        );
    }

    #[Test]
    public function marquee_animation_speed_default_is_numeric_not_string(): void
    {
        $source = $this->fileContents('Modules/Marquee/Filament/MarqueeModuleSettings.php');
        $this->assertNotSame('', $source);

        $anchor = "TextInput::make('options.animationSpeed')";
        $pos = strpos($source, $anchor);
        $this->assertNotFalse($pos, "Anchor not found: {$anchor}");

        // Bound the slice at the next field declaration so the regression
        // guard does not capture sibling fields' legitimate non-numeric
        // defaults (textWeight + textStyle both use ->default('normal')
        // and have no ->numeric() constraint).
        $nextField = strpos($source, "TextInput::make('options.textWeight')", $pos);
        $this->assertNotFalse($nextField, 'textWeight sibling anchor missing — file shape changed.');
        $slice = substr($source, $pos, $nextField - $pos);

        $this->assertStringContainsString(
            '->numeric()',
            $slice,
            'animationSpeed retains the ->numeric() validation constraint.'
        );
        $this->assertStringContainsString(
            '->default(100)',
            $slice,
            'animationSpeed default must be numeric 100 matching MarqueeModule.php runtime fallback.'
        );

        $sliceWithoutComments = preg_replace('~//[^\n]*~', '', $slice);
        $sliceWithoutComments = preg_replace('~/\*.*?\*/~s', '', (string) $sliceWithoutComments);

        $this->assertDoesNotMatchRegularExpression(
            "/->default\(\s*'normal'\s*\)/",
            (string) $sliceWithoutComments,
            "Regression: animationSpeed default reverted to the string 'normal' which fails ->numeric() validation."
        );
    }

    #[Test]
    public function marquee_runtime_fallback_remains_100(): void
    {
        // Cross-check: source ship in MarqueeModuleSettings.php must agree
        // with MarqueeModule.php runtime fallback. If runtime ever changes
        // the fallback value, the form default needs the same change.
        $source = $this->fileContents('Modules/Marquee/Microweber/MarqueeModule.php');
        $this->assertNotSame('', $source);

        $this->assertMatchesRegularExpression(
            "/getOption\(\s*'animationSpeed'\s*,\s*100\s*\)/",
            $source,
            'MarqueeModule runtime fallback for animationSpeed must remain 100 to match the form default.'
        );
    }
}
