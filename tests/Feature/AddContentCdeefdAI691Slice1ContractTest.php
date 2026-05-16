<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-cdeefd / AI-691 — Add-Content Modal Slice 1
 *
 * Designer spec ref: `designer-agent/output/add-content-modal-spec-
 * 2026-05-16.md` §2 key-moves 1, 6 + §A1, §A7.
 *
 * Two changes shipped (third change deferred — see docblock below):
 *
 *   1. Drop "New" prefix from card titles in
 *      `AdminLiveEditPage::addContentAction`. "New Page" → "Page",
 *      "New Post" → "Post", "New Product" → "Product", "New Image"
 *      → "Image", "New Category" → "Category". The modal heading
 *      is already "Add new content" — the per-card "New" was three
 *      syllables of waste per row (§A7).
 *
 *   2. Remove visible body description from cards in
 *      `add-content-modal.blade.php`. Description preserved as the
 *      `title=` attribute (native browser tooltip on hover) AND
 *      aria-label (accessibility tree). Cards now title-only — visual
 *      scan is twice as fast (§A1).
 *
 * Deferred from this slice (flagged to designer):
 *
 *   3. §A6 "delete Cancel button" — conflicts with prior NOVICE #3
 *      decision (task-2026-05-13-899d57) that explicitly keeps the
 *      Cancel button for mobile thumb-reach. Mobile bottom-sheet
 *      (slice 5 / AI-695) is the natural resolution: when the sheet
 *      with drag-handle ships, both X and Cancel become redundant.
 *      Removing Cancel now creates a temporary mobile-only regression
 *      window. Deferred to designer authority — see SHIP report sent
 *      with this commit.
 */
class AddContentCdeefdAI691Slice1ContractTest extends TestCase
{
    private string $page;
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->page = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php'
        ));
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        ));
    }

    #[Test]
    public function card_titles_drop_the_new_prefix(): void
    {
        $titlesAfter = [
            "'title' => 'Page',",
            "'title' => 'Post',",
            "'title' => 'Product',",
            "'title' => 'Image',",
            "'title' => 'Category',",
        ];
        foreach ($titlesAfter as $needle) {
            $this->assertStringContainsString($needle, $this->page,
                "Card title must drop the 'New' prefix — expected: {$needle}");
        }
    }

    #[Test]
    public function legacy_new_prefixed_titles_are_gone(): void
    {
        // Regression guard — the "New X" forms must not return.
        $legacy = [
            "'title' => 'New Page'",
            "'title' => 'New Post'",
            "'title' => 'New Product'",
            "'title' => 'New Image'",
            "'title' => 'New Category'",
        ];
        foreach ($legacy as $needle) {
            $this->assertStringNotContainsString($needle, $this->page,
                "Legacy '{$needle}' must not return — A7 explicit drop.");
        }
    }

    #[Test]
    public function add_a_block_title_unchanged(): void
    {
        // The inline-action card title is the only one that's allowed
        // to read as a full sentence (designer spec §A3 treats it as
        // the single primary in the "on this page" group).
        $this->assertStringContainsString(
            "'title' => 'Add a block to this page',",
            $this->page,
            'Add-a-block card title must stay as the full sentence (spec §A3).'
        );
    }

    #[Test]
    public function card_body_text_div_removed(): void
    {
        // The visible body-text <div class="text-xs text-gray-500 ...">
        // that previously held $action['description'] must be gone.
        // We probe by checking the specific class pattern that was
        // present before AI-691.
        $this->assertDoesNotMatchRegularExpression(
            '/<div[^>]*class="text-xs text-gray-500 dark:text-gray-400 leading-snug"[^>]*>\s*\{\{\s*\$action\[\'description\'\]\s*\}\}\s*<\/div>/s',
            $this->blade,
            'The card-body description <div> must be removed (A1 — title only).'
        );
    }

    #[Test]
    public function description_preserved_as_title_attribute(): void
    {
        // `title="{{ $action['description'] }}"` provides the native
        // browser tooltip on hover for sighted users + survives the
        // body-text removal for keyboard / screen-reader users.
        $this->assertStringContainsString(
            'title="{{ $action[\'description\'] }}"',
            $this->blade,
            'Description must be preserved as the title= attribute on the card button.'
        );
    }

    #[Test]
    public function aria_label_still_carries_full_description(): void
    {
        // Belt-and-braces — aria-label was already on the card before
        // AI-691; this test pins it as a permanent contract so a future
        // edit doesn't strip the accessibility tree.
        $this->assertStringContainsString(
            'aria-label="{{ $action[\'title\'] }}: {{ $action[\'description\'] }}"',
            $this->blade
        );
    }

    #[Test]
    public function task_id_marker_pinned_for_audit_grep(): void
    {
        // Should appear in both files (PHP + Blade) for cross-surface
        // audit grep per project convention.
        $this->assertStringContainsString('task-2026-05-16-cdeefd', $this->page);
        $this->assertStringContainsString('task-2026-05-16-cdeefd', $this->blade);
    }

    #[Test]
    public function cancel_button_kept_pending_designer_a6_resolution(): void
    {
        // Defensive note: §A6 says delete the Cancel button, but the
        // prior NOVICE #3 decision (task-2026-05-13-899d57) keeps it
        // for mobile thumb-reach. This test pins that Cancel stays
        // until designer resolves the conflict explicitly. When the
        // designer green-lights deletion, swap this assertion for
        // the inverse and update the contract.
        $this->assertStringContainsString(
            "->modalCancelActionLabel('Cancel')",
            $this->page,
            'Cancel button kept pending designer §A6 vs NOVICE #3 resolution. '
            . 'Will revisit when slice 5 / AI-695 mobile bottom-sheet replaces '
            . 'the mobile dismiss pattern (drag-handle + ESC + backdrop).'
        );
    }
}
