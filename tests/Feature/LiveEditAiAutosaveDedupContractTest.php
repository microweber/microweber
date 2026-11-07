<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract: AI edits auto-save (no beforeunload "unsaved changes" prompt), and
 * add_section de-dupes the whole-page-rebuilt-with-reworded-copy case by heading.
 */
class LiveEditAiAutosaveDedupContractTest extends TestCase
{
    private string $src = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path('Modules/Ai/resources/assets/js/mw-ai.js'));
    }

    #[Test]
    public function saving_clears_the_unsaved_changes_flag(): void
    {
        $this->assertStringContainsString('clearUnsavedFlag', $this->src, 'An auto-save must clear the unsaved-changes flag.');
        $this->assertStringContainsString('askusertostay = false', $this->src, 'The beforeunload flag must be cleared so no prompt fires.');
        // saveCanvas() delegates to it (both the sync chat save + the async swarm save path).
        $this->assertStringContainsString('this.clearUnsavedFlag()', $this->src, 'saveCanvas must clear the flag after persisting.');
    }

    #[Test]
    public function add_section_dedupes_by_primary_heading(): void
    {
        // The reworded-duplicate case the full-text match misses.
        $this->assertStringContainsString('duplicate section (same heading) skipped', $this->src);
        $this->assertStringContainsString('h1, h2, h3', $this->src, 'Dedup must look at the section primary heading.');
    }
}
