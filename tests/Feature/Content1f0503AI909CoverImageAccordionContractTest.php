<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-1f0503 — Create page modal overflow fix.
 *
 * Problem: In the live-edit "Create page" modal the MwMediaBrowser component
 * (~200px tall) sat between Title and "Page setup" (Template + Layout + iframe),
 * pushing Template, Layout, and the template preview iframe below the fold.
 *
 * Fix:
 *   1. ContentResource.php::formArrayCompact() — wrapped mediaSection() in a
 *      new collapsed Section('Cover image') with ->collapsible()->collapsed(),
 *      same pattern as the existing "More options" accordion.
 *   2. live-edit-classes.css — added .mw-content-form-modal .preview_frame_wrapper_holder
 *      max-height: 160px cap so the template preview iframe fits above the fold.
 *
 * Style: file-system reads only — no DB / Filament boot.
 */
class Content1f0503AI909CoverImageAccordionContractTest extends TestCase
{
    private const CONTENT_RESOURCE = 'Modules/Content/Filament/Admin/ContentResource.php';
    private const CSS_SRC = 'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css';
    private const BUNDLE = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $src;
    private string $srcStripped;
    private string $css;
    private string $cssStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->src = (string) file_get_contents(base_path(self::CONTENT_RESOURCE));

        $s = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src) ?? $this->src;
        $this->srcStripped = preg_replace('~//[^\n]*~', '', $s) ?? $s;

        $this->css = (string) file_get_contents(base_path(self::CSS_SRC));
        $c = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->css) ?? $this->css;
        $this->cssStripped = preg_replace('~//[^\n]*~', '', $c) ?? $c;

        $this->bundle = file_exists(base_path(self::BUNDLE))
            ? (string) file_get_contents(base_path(self::BUNDLE))
            : '';
    }

    // ─── PHP — accordion structure ─────────────────────────────────────────

    #[Test]
    public function cover_image_section_is_collapsible(): void
    {
        $this->assertMatchesRegularExpression(
            '~Section::make\([^)]*Cover image[^)]*\)[\s\S]*?->collapsible\(\)~s',
            $this->srcStripped,
            'formArrayCompact must declare a collapsible Cover image Section.'
        );
    }

    #[Test]
    public function cover_image_section_starts_collapsed(): void
    {
        $this->assertMatchesRegularExpression(
            '~Section::make\([^)]*Cover image[^)]*\)[\s\S]*?->collapsed\(\)~s',
            $this->srcStripped,
            'Cover image Section must start collapsed (->collapsed()).'
        );
    }

    #[Test]
    public function cover_image_section_has_photo_icon(): void
    {
        $this->assertMatchesRegularExpression(
            '~Section::make\([^)]*Cover image[^)]*\)[\s\S]*?heroicon-m-photo~s',
            $this->srcStripped,
            'Cover image Section must use heroicon-m-photo icon.'
        );
    }

    #[Test]
    public function media_section_is_inside_cover_image_accordion(): void
    {
        // The cover-image accordion wraps the mediaSection() call.
        // Verify the accordion appears before the mediaSection call in source.
        $coverPos = strpos($this->srcStripped, 'Cover image');
        $mediaPos = strpos($this->srcStripped, 'mw-fb-media-section');

        $this->assertNotFalse($coverPos, 'Cover image Section must be present in formArrayCompact.');
        $this->assertNotFalse($mediaPos, 'mw-fb-media-section must still be present.');
        $this->assertGreaterThan(
            $coverPos,
            $mediaPos,
            'mw-fb-media-section must appear AFTER the Cover image accordion header — it is nested inside it.'
        );
    }

    #[Test]
    public function cover_image_accordion_has_extra_class(): void
    {
        $this->assertStringContainsString(
            'mw-fb-cover-image-accordion',
            $this->srcStripped,
            'Cover image Section must carry mw-fb-cover-image-accordion class for CSS scoping.'
        );
    }

    #[Test]
    public function more_options_accordion_still_present_and_collapsed(): void
    {
        $this->assertStringContainsString(
            "mw-fb-more-options",
            $this->srcStripped,
            'Existing More options accordion must still carry mw-fb-more-options class.'
        );
        // Verify it is still collapsed
        $moreOptionsPos = strpos($this->srcStripped, 'More options');
        $collapseSlice = substr($this->srcStripped, $moreOptionsPos, 400);
        $this->assertStringContainsString(
            '->collapsed()',
            $collapseSlice,
            'More options accordion must still start collapsed.'
        );
    }

    #[Test]
    public function task_marker_present_in_php_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-1f0503',
            $this->src,
            'ContentResource.php must carry the task-2026-05-22-1f0503 marker.'
        );
    }

    // ─── CSS — template preview iframe cap ────────────────────────────────

    #[Test]
    public function preview_frame_wrapper_max_height_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-content-form-modal\s+\.preview_frame_wrapper_holder\s*\{[^}]*max-height\s*:\s*160px~s',
            $this->cssStripped,
            'live-edit-classes.css must declare max-height: 160px on .mw-content-form-modal .preview_frame_wrapper_holder.'
        );
    }

    #[Test]
    public function preview_frame_wrapper_overflow_hidden(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-content-form-modal\s+\.preview_frame_wrapper_holder\s*\{[^}]*overflow\s*:\s*hidden~s',
            $this->cssStripped,
            'The preview iframe cap must include overflow: hidden.'
        );
    }

    #[Test]
    public function preview_frame_wrapper_scoped_to_content_form_modal(): void
    {
        // Rule must NOT appear as a global selector (without .mw-content-form-modal scope).
        // Strip CSS comments then check the rule is always prefixed.
        $occurrences = preg_match_all(
            '~\.preview_frame_wrapper_holder\s*\{[^}]*max-height~s',
            $this->cssStripped
        );
        $scopedOccurrences = preg_match_all(
            '~\.mw-content-form-modal\s+\.preview_frame_wrapper_holder\s*\{[^}]*max-height~s',
            $this->cssStripped
        );
        $this->assertSame(
            $occurrences,
            $scopedOccurrences,
            'Every max-height rule on .preview_frame_wrapper_holder must be scoped to .mw-content-form-modal.'
        );
    }

    #[Test]
    public function task_marker_present_in_css(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-1f0503',
            $this->css,
            'live-edit-classes.css must carry the task-2026-05-22-1f0503 marker.'
        );
    }

    #[Test]
    public function css_rule_present_in_served_bundle(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Bundle absent — run npm run build in microweber-filament-theme.');
        }
        $this->assertStringContainsString(
            '.mw-content-form-modal .preview_frame_wrapper_holder',
            $this->bundle,
            'Served bundle must include the .preview_frame_wrapper_holder max-height cap.'
        );
    }

    #[Test]
    public function bundle_mtime_not_older_than_css_source(): void
    {
        if (! file_exists(base_path(self::BUNDLE))) {
            $this->markTestSkipped('Bundle absent.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::CSS_SRC)),
            filemtime(base_path(self::BUNDLE)),
            'Served bundle mtime must be >= source mtime — run npm run build.'
        );
    }
}
