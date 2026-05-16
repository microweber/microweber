<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-46d1ac / AI-741 — image upload modal: 5 small
 * copy + affordance fixes. Jira:
 *   https://microweber.atlassian.net/browse/AI-741
 *
 * Designer dispatch 2026-05-16T15:54:51 (Medium polish).
 *
 * Scope shipped in this slice (3 of 5 fixes — JS string surface):
 *   2. "OK" footer button → "Insert image" (verb-led).
 *   3. "Add file" button → "Choose file" (verb-led).
 *   4. "or drop file to upload" → "or drag and drop an image here
 *      · JPG, PNG, GIF, WebP up to 10MB" (inline format + size hint).
 *
 * Deferred to AI-741-followup:
 *   1. "Select Image" → "Select image". Recon: every occurrence
 *      of the dialog title across `filepicker.js`, `quick-ai-edit.js`,
 *      `handle-bg-image.js`, `element-actions.js`, `single-file-
 *      picker-component.js` is ALREADY "Select image" (sentence
 *      case). Designer may have audited a translated string or a
 *      stale rendered build. No source-side work required for #1.
 *   5. Drop-zone two-icons → one centred upload-cloud icon. Lives
 *      in CSS (`mw-file-drop-zone-img` background-image stack),
 *      needs visual-asset swap which is out of scope for a copy
 *      polish slice.
 */
class LiveEdit46d1acAI741ImageModalCopyContractTest extends TestCase
{
    private string $filepicker;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filepicker = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
        // Vite-built bundle (runtime probe per SOUL #108).
        $bundlePath = base_path(
            'packages/frontend-assets/resources/dist/build/admin.js'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — new labels present in source
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ok_button_label_renamed_to_insert_image(): void
    {
        $this->assertMatchesRegularExpression(
            "/okLabel:\\s*mw\\.lang\\(['\"]Insert image['\"]\\)/",
            $this->filepicker,
            'okLabel must be "Insert image" per AI-741 fix #2.'
        );
    }

    #[Test]
    public function add_file_button_label_renamed_to_choose_file(): void
    {
        // All three occurrences (big-zone primary button, small-
        // zone link, dropdown-mode data-title) must read "Choose file".
        $count = preg_match_all(
            "/mw\\.lang\\(['\"]Choose file['\"]\\)/",
            $this->filepicker,
            $m
        );
        $this->assertGreaterThanOrEqual(
            3,
            $count,
            "All 3 'Add file' occurrences must be renamed to 'Choose file' per AI-741 fix #3."
        );
    }

    #[Test]
    public function drop_zone_helper_carries_format_and_size_hint(): void
    {
        // The new helper-text label includes the inline format
        // + size hint per designer dispatch fix #4.
        $expected = 'or drag and drop an image here · JPG, PNG, GIF, WebP up to 10MB';
        $this->assertStringContainsString(
            $expected,
            $this->filepicker,
            'Drop-zone helper text must read "or drag and drop an image here · JPG, PNG, GIF, WebP up to 10MB" per AI-741 fix #4.'
        );
        // Both big-zone + small-zone occurrences must be migrated.
        $count = substr_count($this->filepicker, $expected);
        $this->assertGreaterThanOrEqual(
            2,
            $count,
            'Both big-zone + small-zone "or drop files to upload" strings must be migrated.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — legacy strings gone from rendered output
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function legacy_ok_label_is_gone(): void
    {
        // Strip block + line comments before scanning so the
        // task-marker prose doesn't false-match.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->filepicker);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        $this->assertDoesNotMatchRegularExpression(
            "/okLabel:\\s*mw\\.lang\\(['\"]OK['\"]\\)/",
            $rules,
            'Legacy okLabel: mw.lang("OK") must be gone.'
        );
    }

    #[Test]
    public function legacy_add_file_label_is_gone(): void
    {
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->filepicker);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        $this->assertDoesNotMatchRegularExpression(
            "/mw\\.lang\\(['\"]Add file['\"]\\)/",
            $rules,
            'Legacy mw.lang("Add file") must be replaced everywhere — fix #3.'
        );
    }

    #[Test]
    public function legacy_drop_files_helper_is_gone(): void
    {
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->filepicker);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        $this->assertDoesNotMatchRegularExpression(
            "/mw\\.lang\\(['\"]or drop files to upload['\"]\\)/",
            $rules,
            'Legacy "or drop files to upload" helper must be replaced — fix #4.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — bundle runtime probe (SOUL #108)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_new_labels(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Vite admin.js bundle not present — run `cd packages/frontend-assets && npm run build` to enable runtime probe.');
        }
        $this->assertStringContainsString(
            'Insert image',
            $this->bundle,
            'Vite-built admin.js must carry the "Insert image" label.'
        );
        $this->assertStringContainsString(
            'Choose file',
            $this->bundle,
            'Vite-built admin.js must carry the "Choose file" label.'
        );
        $this->assertStringContainsString(
            'JPG, PNG, GIF, WebP up to 10MB',
            $this->bundle,
            'Vite-built admin.js must carry the inline format + size hint.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + deferred-followup hints
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai741_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-46d1ac', $this->filepicker);
        $this->assertStringContainsString('AI-741', $this->filepicker);
    }
}
