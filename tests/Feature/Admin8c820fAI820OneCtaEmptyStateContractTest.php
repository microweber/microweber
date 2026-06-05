<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-8c820f / AI-820 — one-CTA-per-empty-state contract pin
 * rollout. Jira: https://microweber.atlassian.net/browse/AI-820
 *
 * Extends the AI-796 Slice C pin shape
 * (Cart0e6cfaAI796...::slice_c_one_cta_only_in_empty_state, which pins
 * substr_count('.mw-cart-empty-cta') === 1 on the cart Livewire empty state)
 * to three more surfaces where the AI-759 competing-CTA anti-pattern was
 * identified. Purely ADDITIVE regression guards — NO UI changes. Each test
 * pins exactly ONE primary affordance per empty state, matching current code.
 *
 * Surfaces:
 *   1. Orders admin empty state — the shared empty-state.blade.php Order
 *      branch (modelName = Order) emits exactly ONE .mw-table-empty-cta,
 *      identified by the unique orders.create route (the file carries one
 *      .mw-table-empty-cta per model branch, so we pin on the route).
 *   2. Live-edit "+ ADD" Create modal no-results empty state — message-only;
 *      the create affordances ARE the content-type cards, so the no-search-
 *      match block must NOT introduce a competing primary CTA.
 *   3. Comments admin list — exactly ONE create CTA (single CreateAction in
 *      the header; no duplicated empty-state CTA).
 *
 * Related: AI-796 (canonical pin shape), AI-759 (competing-CTA anti-pattern
 * first instance), AI-816 (Live-edit Create base class — pinned post-fix),
 * LESSONS Stage-1 "ONE primary CTA per empty state" project-wide rule.
 */
class Admin8c820fAI820OneCtaEmptyStateContractTest extends TestCase
{
    private string $emptyState;
    private string $addContentModal;
    private string $listComments;
    private string $cartLivewire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emptyState = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/filament/admin/empty-state.blade.php'
        ));
        $this->addContentModal = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        ));
        $this->listComments = (string) file_get_contents(base_path(
            'Modules/Comments/Filament/Resources/CommentResource/Pages/ListComments.php'
        ));
        $this->cartLivewire = (string) file_get_contents(base_path(
            'Modules/Checkout/resources/views/livewire/cart-items.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Surface 1 — Orders admin empty state
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function surface_orders_one_cta_only_in_empty_state(): void
    {
        // The Orders empty state (shared empty-state.blade.php, modelName =
        // Order) must emit exactly ONE primary CTA — the orders.create anchor
        // carrying .mw-table-empty-cta. The file holds one .mw-table-empty-cta
        // per model branch, so we pin on the branch-unique orders.create route.
        $count = substr_count($this->emptyState, "route('filament.admin.resources.orders.create')");
        $this->assertSame(
            1,
            $count,
            'Orders empty state must emit exactly ONE orders.create CTA (one-CTA-per-empty-state rule).'
        );

        // And that single CTA must carry the canonical empty-state CTA class.
        $this->assertMatchesRegularExpression(
            '/route\(\'filament\.admin\.resources\.orders\.create\'\)[^>]*class="mw-table-empty-cta"/s',
            $this->emptyState,
            'The Orders empty-state CTA must carry the canonical .mw-table-empty-cta class.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Surface 2 — Live-edit "+ ADD" Create modal no-results empty state
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function surface_live_edit_create_modal_no_competing_cta(): void
    {
        // The Live-edit Create modal's no-results state is message-only: the
        // create affordances are the content-type cards themselves, so the
        // no-search-match block must NOT introduce a competing primary CTA.
        // Pin: exactly ONE no-results empty-state element...
        $count = substr_count($this->addContentModal, 'mw-add-content-modal-empty');
        $this->assertSame(
            1,
            $count,
            'Live-edit Create modal must have exactly ONE no-results empty-state element.'
        );

        // ...and no admin-table empty CTA class leaks onto the modal surface
        // (that would be a second, competing primary CTA in the empty state).
        $this->assertStringNotContainsString(
            'mw-table-empty-cta',
            $this->addContentModal,
            'Live-edit Create modal must not carry a competing .mw-table-empty-cta primary CTA.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Surface 3 — Comments admin list
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function surface_comments_one_create_cta(): void
    {
        // The Comments admin list exposes a single primary create affordance —
        // one CreateAction in the header — with no duplicated empty-state CTA.
        // Pin exactly ONE CreateAction::make() (the AI-759 competing-CTA guard).
        $count = substr_count($this->listComments, 'CreateAction::make()');
        $this->assertSame(
            1,
            $count,
            'Comments list must expose exactly ONE create CTA (no duplicated empty-state CTA).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Baseline — the AI-796 canonical pin still holds (green-run sibling)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function baseline_ai796_cart_one_cta_pin_still_holds(): void
    {
        // AI-820 acceptance: all three new pins pass in the same green run as
        // AI-796's slice_c_one_cta_only_in_empty_state. Re-assert the canonical
        // pin here so a regression on the source-of-truth surface is caught
        // alongside the rollout.
        $count = substr_count($this->cartLivewire, 'class="mw-cart-empty-cta"');
        $this->assertSame(
            1,
            $count,
            'AI-796 baseline: cart Livewire empty state must still emit exactly ONE .mw-cart-empty-cta.'
        );
    }
}
