<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-8c8d0b / AI-855 — Media Library modal polish bundle.
 * Jira: https://microweber.atlassian.net/browse/AI-855
 *
 * Round 18 deeper Upload Image Modal audit per user "now eval the upload
 * image modal". 3 defects in the new Media Library iframe modal
 * (/admin/media-library), bundled in one ship:
 *
 *   Defect 1 (no dropzone affordance): the upload zone existed but was
 *     gated by `x-show="showUploadZone || dragOver"` with showUploadZone
 *     defaulting to false. Users saw no visible drag-target until they
 *     first clicked the Upload button -- 3 extra clicks vs the drag-
 *     from-desktop baseline UX. Fix: showUploadZone defaults to true so
 *     the dropzone is persistently visible. Existing toggle behaviour
 *     preserved. Class .mw-media-library-dropzone appended alongside
 *     .mw-media-upload-zone for designer's contract-test selector.
 *
 *   Defect 2 (Upload not brand-blue, sibling of AI-816): the Upload
 *     button rendered background $mw-text-primary (#182433 dark) --
 *     competing visually with neighbouring secondary buttons. Fix:
 *     consume rgba(var(--primary-500), 1) so the AI-819 :root override
 *     flows through to brand-blue #0d6efd at runtime. !important
 *     defeats Filament's default .fi-btn cascade per the AI-819 CHANGE
 *     pattern at microweber-theme-v3.scss:2914.
 *
 *   Defect 3 (search a11y, sibling of AI-817): search input had
 *     placeholder="Search media..." but no aria-label, no <label for>.
 *     WCAG 3.3.2 Level A fail (placeholder is not a label). Fix:
 *     aria-label="Search media" added.
 *
 * Pairs with AI-854 (P1 strict-mode ReferenceError on the legacy upload
 * path); different modal, sibling upload-UX family.
 */
class MediaLibrary8c8d0bAI855UploadModalPolishContractTest extends TestCase
{
    private const BLADE = 'Modules/MediaLibrary/resources/views/filament/admin/pages/media-library-page.blade.php';
    private const SCSS = 'packages/microweber-filament-theme/resources/assets/css/microweber-theme-v3.scss';

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Defect 1: persistent dropzone affordance
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function show_upload_zone_defaults_true(): void
    {
        $source = $this->read(self::BLADE);

        // The root x-data block declares showUploadZone: true (was false
        // pre-fix). Whitespace-tolerant regex.
        $this->assertMatchesRegularExpression(
            '/x-data\s*=\s*"\s*\{\s*showUploadZone\s*:\s*true\s*,/s',
            $source,
            'AI-855 Defect 1: root x-data MUST declare showUploadZone: true so the dropzone is visible by default. Pre-fix default was false; users saw no drag-target without first clicking Upload.'
        );
    }

    #[Test]
    public function dropzone_class_present_on_upload_zone(): void
    {
        $source = $this->read(self::BLADE);

        $this->assertMatchesRegularExpression(
            '/class\s*=\s*"mw-media-upload-zone\s+mw-media-library-dropzone"/',
            $source,
            'AI-855 Defect 1: the upload zone MUST carry both .mw-media-upload-zone (legacy + CSS) and .mw-media-library-dropzone (per designer contract-test selector).'
        );
    }

    #[Test]
    public function dropzone_carries_html5_drag_handlers(): void
    {
        $source = $this->read(self::BLADE);

        // Native HTML5 drag handlers (dragover + dragleave + drop) on
        // the dropzone wrapper. Pre-fix shape preserved.
        $this->assertStringContainsString(
            'x-on:dragover.prevent="dragOver = true"',
            $source,
            'AI-855 Defect 1: dropzone MUST keep its dragover handler.'
        );
        $this->assertStringContainsString(
            'x-on:dragleave.prevent="dragOver = false"',
            $source,
            'AI-855 Defect 1: dropzone MUST keep its dragleave handler.'
        );
        $this->assertStringContainsString(
            'x-on:drop.prevent="',
            $source,
            'AI-855 Defect 1: dropzone MUST keep its drop handler.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Defect 2: Upload button consumes brand-blue
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function upload_btn_scss_consumes_primary_500_token(): void
    {
        $source = $this->read(self::SCSS);

        // Slice the .mw-media-upload-btn rule body. Fixed-length lookahead
        // (per the AI-816 LESSONS slice-bounding pattern) -- 800 chars
        // covers the rule body + :hover modifier.
        //
        // Updated 2026-06: the SCSS now declares the selector more than
        // once (an earlier `.mw-media-upload-btn { height: 36px; }` sizing
        // rule precedes the brand-blue styling block). Anchor on the
        // occurrence whose body actually carries the `background:`
        // declaration rather than the first textual match.
        $anchorPos = strpos($source, ".mw-media-upload-btn {\n  display: flex;");
        if ($anchorPos === false) {
            $anchorPos = strpos($source, '.mw-media-upload-btn {');
        }
        $this->assertNotFalse(
            $anchorPos,
            'AI-855: .mw-media-upload-btn rule must exist in microweber-theme-v3.scss.'
        );

        $slice = substr($source, $anchorPos, 800);

        $this->assertMatchesRegularExpression(
            '/background:\s*rgba\(\s*var\(\s*--primary-500\s*\)\s*,\s*1\s*\)\s*!important/',
            $slice,
            'AI-855 Defect 2: .mw-media-upload-btn MUST consume rgba(var(--primary-500), 1) !important. Pre-fix shape was background: $mw-text-primary (dark) which competed with neighbouring secondary buttons.'
        );

        $this->assertMatchesRegularExpression(
            '/&:hover\s*\{\s*background:\s*rgba\(\s*var\(\s*--primary-600\s*\)\s*,\s*1\s*\)\s*!important/',
            $slice,
            'AI-855 Defect 2: .mw-media-upload-btn:hover MUST shift to rgba(var(--primary-600), 1) !important per AI-819 :hover lineage.'
        );
    }

    #[Test]
    public function upload_btn_legacy_dark_background_gone(): void
    {
        $source = $this->read(self::SCSS);

        // Selector-self-match guard (18+ session-recurrences): strip
        // // and /* */ comments before negative assertion so docblock
        // prose mentioning the legacy `$mw-text-primary` token doesn't
        // false-fail.
        $stripped = preg_replace('~//.*$~m', '', $source);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);

        // Slice the post-fix .mw-media-upload-btn rule.
        $anchorPos = strpos($stripped, '.mw-media-upload-btn {');
        $this->assertNotFalse($anchorPos, 'AI-855 slice: rule anchor must be present after comment-strip.');

        $slice = substr($stripped, $anchorPos, 800);

        // Pre-fix `background: $mw-text-primary;` MUST be gone (replaced
        // by the rgba(var(--primary-500)...) form pinned in Group B).
        $this->assertDoesNotMatchRegularExpression(
            '/background:\s*\$mw-text-primary\s*;/',
            $slice,
            'AI-855 Defect 2 regression guard: .mw-media-upload-btn MUST NOT carry `background: $mw-text-primary;` (replaced by brand-blue token consumer).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Defect 3: search input aria-label
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function search_input_carries_aria_label(): void
    {
        $source = $this->read(self::BLADE);

        // Pin the aria-label specifically on the .mw-media-search-input
        // (the toolbar search). Locate the wire:model.live.debounce.300ms
        // ="search" anchor (unique to this input) and assert aria-label
        // is in the same input element's attribute list.
        $anchorPos = strpos($source, 'wire:model.live.debounce.300ms="search"');
        $this->assertNotFalse(
            $anchorPos,
            'AI-855: search input wire:model anchor must be present.'
        );

        // Walk back to <input opening tag, then forward to closing />.
        $tagStart = strrpos(substr($source, 0, $anchorPos), '<input');
        $this->assertNotFalse(
            $tagStart,
            'AI-855: <input opening tag for the search input must be present.'
        );

        $tagEnd = strpos($source, '/>', $tagStart);
        $this->assertNotFalse(
            $tagEnd,
            'AI-855: search input self-closing tag end must be present.'
        );

        $inputTag = substr($source, $tagStart, $tagEnd - $tagStart + 2);

        $this->assertMatchesRegularExpression(
            '/\baria-label\s*=\s*"Search media"/',
            $inputTag,
            'AI-855 Defect 3: search input MUST carry aria-label="Search media" (WCAG 3.3.2 Level A; placeholder is not a label).'
        );

        // Placeholder is preserved (visual hint, doesn't replace label).
        $this->assertMatchesRegularExpression(
            '/\bplaceholder\s*=\s*"Search media\.\.\."/',
            $inputTag,
            'AI-855 Defect 3: search input placeholder preserved alongside aria-label.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — task-id + AI-855 markers (audit grep contract) across
    // both surfaces (blade + scss)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_markers_present_in_blade(): void
    {
        $source = $this->read(self::BLADE);
        $this->assertStringContainsString(
            'task-2026-05-17-8c8d0b',
            $source,
            'AI-855: blade view MUST carry the AI-855 task-id marker.'
        );
        $this->assertStringContainsString(
            'AI-855',
            $source,
            'AI-855: blade view MUST cite the AI-855 ticket ID.'
        );
    }

    #[Test]
    public function task_id_markers_present_in_scss(): void
    {
        $source = $this->read(self::SCSS);
        $this->assertStringContainsString(
            'task-2026-05-17-8c8d0b',
            $source,
            'AI-855: SCSS MUST carry the AI-855 task-id marker near the .mw-media-upload-btn rule.'
        );
        $this->assertStringContainsString(
            'AI-855',
            $source,
            'AI-855: SCSS MUST cite the AI-855 ticket ID.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — sibling-family cross-citations (AI-816 + AI-817 + AI-819
    //          lineage acknowledgments)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function sibling_family_lineage_cited_in_scss(): void
    {
        $source = $this->read(self::SCSS);

        // Slice the AI-855 docblock + .mw-media-upload-btn rule for
        // lineage citation check (fixed-length lookahead).
        $anchorPos = strpos($source, 'task-2026-05-17-8c8d0b / AI-855');
        $this->assertNotFalse($anchorPos, 'AI-855: task-id marker anchor must be present.');

        $slice = substr($source, $anchorPos, 1200);

        $this->assertStringContainsString(
            'AI-816',
            $slice,
            'AI-855 Defect 2 lineage: SCSS docblock MUST cite AI-816 (primary-CTA color contract).'
        );
        $this->assertStringContainsString(
            'AI-819',
            $slice,
            'AI-855 Defect 2 lineage: SCSS docblock MUST cite AI-819 (Filament primary token re-anchor that makes var(--primary-X) resolve to brand-blue).'
        );
    }

    #[Test]
    public function defect_3_lineage_cited_in_blade(): void
    {
        $source = $this->read(self::BLADE);

        $this->assertStringContainsString(
            'AI-817',
            $source,
            'AI-855 Defect 3 lineage: blade docblock MUST cite AI-817 (hidden-label WCAG 3.3.2 sibling family).'
        );
    }
}
