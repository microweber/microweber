<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-AI777 — close the two AI-777 acceptance criteria on the
 * Create/Edit Post form.
 *
 * Issue 1 — "Published" toggle must default to Draft (OFF) on Create, WITH the
 * explanatory helper text the ticket asked for. The draft-first default shipped
 * earlier (AI-778) but without the helper text; this pins both.
 *
 * Issue 2 — the "Live edit" button must never silently lose unsaved work. The
 * button is an EditAction that persists the record first
 * (saveContentAndGoLiveEdit*), so the data is safe; this pins that the LABEL
 * makes that save-first intent explicit ("Save & Live Edit"), matching the
 * CreateContent precedent (AI-1028).
 */
class ContentAI777DraftDefaultAndLiveEditGuardContractTest extends TestCase
{
    private function read(string $relative): string
    {
        $path = base_path($relative);
        $this->assertFileExists($path);

        return (string) file_get_contents($path);
    }

    #[Test]
    public function published_toggle_defaults_to_draft_with_helper_text(): void
    {
        $resource = $this->read('Modules/Content/Filament/Admin/ContentResource.php');

        // Slice the publishedSection() body so the assertions are local to the
        // is_active toggle and don't accidentally match other toggles.
        $start = strpos($resource, 'function publishedSection(');
        $this->assertNotFalse($start, 'publishedSection() must exist.');
        $slice = substr($resource, $start, 2000);

        // The Published control may be a Toggle or Radio. The key
        // contract is that is_active defaults to false/0 on create.
        $this->assertMatchesRegularExpression(
            "/(?:Toggle|Radio)::make\('is_active'\)[\s\S]*?->default\((?:false|0)\)/",
            $slice,
            'The Published (is_active) control must default to false/0 (Draft-first) on Create.'
        );
        $this->assertStringContainsString(
            'Drafts are only visible to you.',
            $slice,
            'The Published control must carry the AI-777 explanatory helper text.'
        );
        // Regression guard: it must NOT default to true/1.
        $this->assertDoesNotMatchRegularExpression(
            "/(?:Toggle|Radio)::make\('is_active'\)[\s\S]*?->default\((?:true|1)\)/",
            $slice,
            'The Published control must not default to true (publish-on-first-save footgun).'
        );
    }

    #[Test]
    public function edit_live_edit_button_saves_first_and_says_so(): void
    {
        $edit = $this->read('Modules/Content/Filament/Admin/ContentResource/Pages/EditContent.php');

        // The button persists the record before opening the editor.
        $this->assertMatchesRegularExpression(
            '/EditAction::make\(\)->action\(\s*\'saveContentAndGoLiveEdit(Iframe)?\'\s*\)/',
            $edit,
            'The Edit "Live edit" button must save the record first (saveContentAndGoLiveEdit*).'
        );
        // And the label states the save-first intent explicitly.
        $this->assertStringContainsString(
            "->label('Save & Live Edit')",
            $edit,
            'The Edit live-edit button label must read "Save & Live Edit" to signal the auto-save.'
        );
    }

    #[Test]
    public function create_live_edit_button_label_preserved(): void
    {
        $create = $this->read('Modules/Content/Filament/Admin/ContentResource/Pages/CreateContent.php');

        // AI-1028 precedent must remain in place.
        $this->assertStringContainsString(
            "->label('Save & Live Edit')",
            $create,
            'The Create live-edit button must keep the explicit "Save & Live Edit" label (AI-1028).'
        );
    }
}
