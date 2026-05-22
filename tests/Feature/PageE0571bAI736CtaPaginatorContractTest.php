<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-e0571b / AI-736 (re-dispatch) — Pages admin:
 * + Add page CTA + Paginator sensible defaults for >10 pages.
 *
 * Context: AI-736 was previously addressed in task-2026-05-17-0b912e
 * (commit 889752e465) which added the CreateAction CTA and paginated(false)
 * for ≤10 pages. The dispatch was re-filed after AI-876 unblocked the page
 * load (removed ->whereNull('deleted_at') — see Admin3b90ddAI876).
 *
 * This ticket adds the missing AC #4: when >10 pages exist, the parent's
 * ->defaultPaginationPageOption(250) produces "Per page 250" — override
 * to ->paginated([10, 25, 50])->defaultPaginationPageOption(25).
 *
 * Acceptance criteria:
 *  AC 1  /admin/pages loads (AI-876 fixed, tracked separately)
 *  AC 2  CreateAction visible in header → "+ Add page"
 *  AC 3  ≤10 pages → paginator hidden (paginated(false))
 *  AC 4  >10 pages → paginator with [10,25,50] defaulting to 25
 *  AC 5  Click "+ Add page" → /admin/pages/create (Filament default for CreateAction)
 *  AC 6  ≥8 tests / ≥20 assertions
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class PageE0571bAI736CtaPaginatorContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $raw = (string) file_get_contents(base_path(
            'Modules/Page/Filament/Resources/PageResource/Pages/ListPages.php'
        ));
        $this->src = $raw;
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', preg_replace('~//[^\n]*~', '', $raw) ?? $raw) ?? $raw;
    }

    // ─── Task markers ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-e0571b', $this->src,
            'ListPages.php must carry the AI-736 re-dispatch task marker.');
    }

    // ─── AC 2: CreateAction CTA ───────────────────────────────────────────────

    #[Test]
    public function create_action_present_in_header(): void
    {
        $this->assertMatchesRegularExpression(
            '~Actions\\\\CreateAction::make\(\)~',
            $this->srcStripped,
            'ListPages must register a Filament CreateAction for the "+ Add page" CTA'
        );
    }

    #[Test]
    public function create_action_has_primary_color(): void
    {
        $this->assertMatchesRegularExpression(
            "~->color\\(\\s*['\"]primary['\"]\\s*\\)~",
            $this->srcStripped,
            'CreateAction must use primary color'
        );
    }

    #[Test]
    public function create_action_label_is_add_page(): void
    {
        $this->assertMatchesRegularExpression(
            "~->label\\(\\s*['\"]\\+\\s*Add page['\"]\\s*\\)~",
            $this->srcStripped,
            'CreateAction must use "+ Add page" label'
        );
    }

    // ─── AC 3: ≤10 pages → paginator hidden ──────────────────────────────────

    #[Test]
    public function table_method_hides_paginator_for_small_page_count(): void
    {
        $this->assertMatchesRegularExpression(
            '~\$pageCount\s*<=\s*10~',
            $this->srcStripped,
            'table() must branch on $pageCount <= 10'
        );
        $this->assertMatchesRegularExpression(
            '~\$table->paginated\(false\)~',
            $this->srcStripped,
            'table() must call $table->paginated(false) when count <= 10'
        );
    }

    // ─── AC 4: >10 pages → sensible pagination options ────────────────────────

    #[Test]
    public function table_method_overrides_pagination_for_large_page_count(): void
    {
        // The else branch must set a sensible [10,25,50] option set
        // so users do not see the parent's "Per page 250" default.
        $this->assertMatchesRegularExpression(
            '~\$table->paginated\(\s*\[\s*10\s*,\s*25\s*,\s*50\s*\]\s*\)~',
            $this->srcStripped,
            'table() else branch must call ->paginated([10, 25, 50])'
        );
    }

    #[Test]
    public function table_method_sets_default_page_option_to_25(): void
    {
        $this->assertMatchesRegularExpression(
            '~->defaultPaginationPageOption\(\s*25\s*\)~',
            $this->srcStripped,
            'table() else branch must call ->defaultPaginationPageOption(25) not 250'
        );
    }

    #[Test]
    public function table_method_does_not_use_250_as_default(): void
    {
        // Regression guard: parent sets ->defaultPaginationPageOption(250);
        // the ListPages override must NOT expose 250 in the >10 branch.
        $this->assertDoesNotMatchRegularExpression(
            '~defaultPaginationPageOption\(\s*250\s*\)~',
            $this->srcStripped,
            'ListPages must not default to 250 — that is the "Per page 250" defect'
        );
    }

    // ─── AC safety: soft-delete query correctness (AI-876 guard) ─────────────

    #[Test]
    public function count_query_uses_is_deleted_not_deleted_at(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            "~->whereNull\\(\\s*['\"]deleted_at['\"]\\s*\\)~",
            $this->srcStripped,
            'Count query must NOT use deleted_at (AI-876 regression guard)'
        );
        $this->assertStringContainsString(
            "'is_deleted', 0",
            $this->srcStripped,
            'Count query must use is_deleted = 0'
        );
    }

    // ─── Prior AI-736 work regression guards ─────────────────────────────────

    #[Test]
    public function homepage_setup_action_still_present(): void
    {
        $this->assertStringContainsString(
            "'setup_homepage'",
            $this->srcStripped,
            'Setup-Homepage action must be preserved (regression guard)'
        );
    }
}
