<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-3a464f — Live Edit save button label tightened.
 *
 * User asked: "in live edit make the sve and pubish button ony save".
 * The button previously read "Save & Publish" with aria-label
 * "Save and publish page (Ctrl+S)" and title "Save & Publish (Ctrl+S)".
 * On narrow viewports the long copy crowded the toolbar; on every
 * viewport the "& Publish" part repeated information already implied
 * by being in the live edit toolbar (the act of saving here IS the
 * publish step — there's no separate publish action).
 *
 * Fix (SaveButton.vue template only — handler `save()` unchanged):
 *   - visible <span> "Save & Publish" → "Save"
 *   - aria-label "Save and publish page (Ctrl+S)" → "Save (Ctrl+S)"
 *   - title "Save & Publish (Ctrl+S)" → "Save (Ctrl+S)"
 *
 * Pins the new strings + a regression guard that the legacy label
 * cannot quietly come back.
 */
class LiveEdit3a464fSaveButtonLabelContractTest extends TestCase
{
    private string $vue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue'
        ));
    }

    #[Test]
    public function button_renders_short_save_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/<span\s+class="font-weight-bold">Save<\/span>/',
            $this->vue,
            'SaveButton visible label must be just "Save".'
        );
    }

    #[Test]
    public function button_aria_label_and_title_are_short_form(): void
    {
        // task-2026-05-16-d2e562 AI-719 update — aria-label collapsed
        // from "Save (Ctrl+S)" to bare verb "Save". The (Ctrl+S)
        // shortcut hint stays on `title=` for desktop hover (and the
        // keyboard binding itself stays in script). Saves ~50px of
        // mobile toolbar width via the task-8149b5 ::after pseudo
        // which renders aria-label as visible text on touch viewports.
        $this->assertMatchesRegularExpression(
            '/aria-label="Save"/',
            $this->vue,
            'aria-label must be bare verb "Save" per AI-719 (was "Save (Ctrl+S)" — shortcut hint moved to title= only).'
        );
        $this->assertMatchesRegularExpression(
            '/title="Save \(Ctrl\+S\)"/',
            $this->vue,
            'title must keep "Save (Ctrl+S)" — desktop hover affordance preserved per AI-719.'
        );
    }

    #[Test]
    public function legacy_publish_copy_is_removed_from_button(): void
    {
        // Regression guard — the previous "Save & Publish" / "Save and
        // publish page" strings on the rendered <button> must not come
        // back. The header docblock at the top of the file still
        // describes the historic name, which is fine.
        $this->assertDoesNotMatchRegularExpression(
            '/<span[^>]*>Save\s*&amp;\s*Publish<\/span>/',
            $this->vue,
            'Visible button label must not regress to "Save & Publish".'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Save and publish page/',
            $this->vue,
            'aria-label must not regress to "Save and publish page".'
        );
    }

    #[Test]
    public function save_handler_remains_wired(): void
    {
        // Functional check — only the LABEL changes, not the click
        // handler. The button still calls save().
        $this->assertStringContainsString('@click="save()"', $this->vue);
        $this->assertStringContainsString('id="save-button"', $this->vue);
    }

    #[Test]
    public function task_id_is_pinned_in_a_comment(): void
    {
        $this->assertStringContainsString('task-2026-05-16-3a464f', $this->vue);
    }
}
