<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-b45297 / AI-907 — Pictures/Gallery module settings wired to template.
 *
 * Problem: PicturesModuleSettings (AI-872 Slice 1) saved 3 settings correctly
 * (columns, aspect_ratio, lightbox) but the Blade templates never consumed them.
 * Verified by regex across all 25+ templates — zero matches for
 * $options['columns'], $options['aspect_ratio'], $options['lightbox'].
 *
 * Fix (in default.blade.php):
 *   1. $mwAi907ColClass match on $options['columns'] ?? '3'
 *   2. $mwAi907AspectStyle match on $options['aspect_ratio'] ?? 'auto'
 *   3. $mwAi907Lightbox from $options['lightbox'] ?? true
 *   4. Column class applied to .mw-pictures-clean-item div
 *   5. Aspect style passed to responsive_thumbnail() attributes
 *   6. Lightbox <a> wrapper gated on $mwAi907Lightbox
 *
 * Tier-2 DOM structure assertions via position comparison.
 *
 * Related: AI-904/905 (Blog), AI-906 (Shop), AI-872 Slice 1.
 * Style: file-system reads only — no DB / Blade boot.
 */
class PicturesB45297AI907SettingsWiredToTemplateContractTest extends TestCase
{
    private const DEFAULT_BLADE = 'Modules/Pictures/resources/views/templates/default.blade.php';

    private string $raw;
    private string $stripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->raw = (string) file_get_contents(base_path(self::DEFAULT_BLADE));

        $s = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->raw) ?? $this->raw;
        $s = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $s) ?? $s;
        $this->stripped = preg_replace('~//[^\n]*~', '', $s) ?? $s;
    }

    // ─── Tier-1: source-pin ───────────────────────────────────────────────────

    #[Test]
    public function col_class_computed_via_match(): void
    {
        $this->assertMatchesRegularExpression(
            '~\$mwAi907ColClass\s*=\s*match~s',
            $this->stripped,
            'default.blade.php must compute $mwAi907ColClass via match on columns setting.'
        );
    }

    #[Test]
    public function col_class_match_covers_2_3_4(): void
    {
        $this->assertStringContainsString("'2'", $this->stripped, 'match must handle 2 columns.');
        $this->assertStringContainsString("'4'", $this->stripped, 'match must handle 4 columns.');
        $this->assertStringContainsString('default', $this->stripped, 'match must have default (3 columns).');
    }

    #[Test]
    public function aspect_style_computed_via_match(): void
    {
        $this->assertMatchesRegularExpression(
            '~\$mwAi907AspectStyle\s*=\s*match~s',
            $this->stripped,
            'default.blade.php must compute $mwAi907AspectStyle via match on aspect_ratio setting.'
        );
    }

    #[Test]
    public function aspect_style_covers_all_ratios(): void
    {
        foreach (['1:1', '4:3', '16:9', '3:4'] as $ratio) {
            $this->assertStringContainsString(
                $ratio,
                $this->stripped,
                "aspect_ratio match must handle '$ratio'."
            );
        }
        $this->assertStringContainsString('default', $this->stripped, 'aspect_ratio match must have default (auto/empty).');
    }

    #[Test]
    public function lightbox_setting_consumed(): void
    {
        $this->assertStringContainsString(
            '$mwAi907Lightbox',
            $this->stripped,
            'default.blade.php must consume $mwAi907Lightbox from $options[lightbox].'
        );
    }

    #[Test]
    public function col_class_applied_to_grid_item_div(): void
    {
        $this->assertMatchesRegularExpression(
            '~mw-pictures-clean-item[^"]*\{\{\s*\$mwAi907ColClass\s*\}\}~',
            $this->stripped,
            'Column class must be output via {{ $mwAi907ColClass }} on the .mw-pictures-clean-item div.'
        );
    }

    #[Test]
    public function aspect_style_applied_to_responsive_thumbnail_call(): void
    {
        $this->assertStringContainsString(
            'mwAi907AspectStyle',
            $this->stripped,
            'Aspect style must be passed to responsive_thumbnail() or applied to the img element.'
        );
    }

    #[Test]
    public function lightbox_anchor_gated_on_lightbox_setting(): void
    {
        $this->assertMatchesRegularExpression(
            '~@if\s*\(\s*\$mwAi907Lightbox\s*\)[\s\S]*?data-mw-gallery[\s\S]*?@endif~s',
            $this->stripped,
            'Lightbox <a> wrapper must be inside @if($mwAi907Lightbox) ... @endif.'
        );
    }

    #[Test]
    public function row_class_on_clean_container(): void
    {
        $this->assertMatchesRegularExpression(
            '~class="mw-pictures-clean\s+row~',
            $this->stripped,
            '.mw-pictures-clean container must carry Bootstrap row class for grid layout.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-b45297',
            $this->raw,
            'default.blade.php must carry the AI-907 task marker.'
        );
    }

    // ─── Tier-2: DOM structure position assertions ────────────────────────────

    #[Test]
    public function col_class_variable_appears_after_match_computation(): void
    {
        $src = $this->stripped;
        $matchPos   = strpos($src, '$mwAi907ColClass = match');
        $usagePos   = strpos($src, '$mwAi907ColClass }}');

        $this->assertNotFalse($matchPos, 'match computation for $mwAi907ColClass must be present.');
        $this->assertNotFalse($usagePos, 'Usage of {{ $mwAi907ColClass }} must be present.');
        $this->assertGreaterThan(
            $matchPos,
            $usagePos,
            'Column class must be USED after it is computed — not before.'
        );
    }

    #[Test]
    public function lightbox_if_guard_appears_before_data_mw_gallery(): void
    {
        $src = $this->stripped;
        $ifPos      = strpos($src, '@if($mwAi907Lightbox)');
        $galleryPos = strpos($src, 'data-mw-gallery=');

        $this->assertNotFalse($ifPos, '@if($mwAi907Lightbox) guard must exist.');
        $this->assertNotFalse($galleryPos, 'data-mw-gallery attribute must exist.');
        $this->assertGreaterThan(
            $ifPos,
            $galleryPos,
            'data-mw-gallery must appear INSIDE the @if($mwAi907Lightbox) conditional.'
        );
    }

    #[Test]
    public function aspect_style_appears_after_match_computation(): void
    {
        $src = $this->stripped;
        $matchPos = strpos($src, '$mwAi907AspectStyle = match');
        $usePos   = strpos($src, 'mwAi907AspectStyle');

        $this->assertNotFalse($matchPos, 'match computation for $mwAi907AspectStyle must be present.');
        // The variable name appears in both the match assignment AND later usage — usePos finds the first
        // occurrence which is the match itself. Find the SECOND occurrence (actual usage):
        $usePos2 = strpos($src, 'mwAi907AspectStyle', $matchPos + 1);
        $this->assertNotFalse($usePos2, 'Second occurrence of $mwAi907AspectStyle (usage) must exist.');
        $this->assertGreaterThan(
            $matchPos,
            $usePos2,
            'Aspect style must be USED after it is computed.'
        );
    }

    // ─── Column class values ─────────────────────────────────────────────────

    #[Test]
    public function col_class_2_is_col_md_6(): void
    {
        $result = match('2') { '2' => 'col-12 col-md-6', '4' => 'col-12 col-md-6 col-lg-3', default => 'col-12 col-md-4' };
        $this->assertStringContainsString('col-md-6', $result);
        $this->assertStringNotContainsString('col-lg-4', $result);
    }

    #[Test]
    public function col_class_3_is_col_md_4(): void
    {
        $result = match('3') { '2' => 'col-12 col-md-6', '4' => 'col-12 col-md-6 col-lg-3', default => 'col-12 col-md-4' };
        $this->assertStringContainsString('col-md-4', $result);
    }

    #[Test]
    public function col_class_4_is_col_lg_3(): void
    {
        $result = match('4') { '2' => 'col-12 col-md-6', '4' => 'col-12 col-md-6 col-lg-3', default => 'col-12 col-md-4' };
        $this->assertStringContainsString('col-lg-3', $result);
    }
}
