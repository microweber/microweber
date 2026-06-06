<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI741 — image upload modal copy/affordance polish.
 *
 * Already fixed (task-2026-05-17-46d1ac) and present in the served admin.js
 * bundle. This guard pins the 5 copy/affordance items so they can't regress:
 *  1. Modal title sentence-case "Select image"
 *  2. Primary footer verb "Insert image" (not generic "OK")
 *  3. File-picker button "Choose file" (not "Add file")
 *  4. Drop-zone hint with inline format + size cap
 *  5. Single drop-zone illustration container (.mw-file-drop-zone-img), not two
 */
class FilepickerAI741ImageModalCopyContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
    }

    #[Test]
    public function modal_title_and_primary_button_are_sentence_case_verb_led(): void
    {
        $this->assertStringContainsString('mw.lang("Select image")', $this->src, 'Title must be sentence-case "Select image".');
        $this->assertStringContainsString('okLabel: mw.lang("Insert image")', $this->src, 'Primary footer button must be verb-led "Insert image".');
    }

    #[Test]
    public function file_button_and_dropzone_hint_are_polished(): void
    {
        $this->assertStringContainsString('mw.lang("Choose file")', $this->src, 'File button must read "Choose file".');
        $this->assertStringContainsString(
            'or drag and drop an image here · JPG, PNG, GIF, WebP up to 10MB',
            $this->src,
            'Drop-zone hint must include the inline format + size cap.'
        );
        // Regression guard: the old generic labels must be gone.
        $this->assertStringNotContainsString('mw.lang("Add file")', $this->src, 'Old "Add file" label must be gone.');
    }

    #[Test]
    public function dropzone_uses_a_single_illustration_container(): void
    {
        // The big uploader uses one .mw-file-drop-zone-img container (one icon),
        // not two stacked icons.
        $this->assertStringContainsString('mw-file-drop-zone-img', $this->src, 'Drop zone must use the single illustration container.');
    }
}
