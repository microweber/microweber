<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport\Tests;

use Livewire\Livewire;
use MicroweberPackages\FilamentModalTeleport\Tests\Fixtures\NestedModalComponent;
use PHPUnit\Framework\Attributes\Test;

/**
 * Livewire integration tests for nested/stacked Filament modals.
 *
 * These tests exercise the actual Livewire + Filament action pipeline to
 * verify that:
 *   1. Single modals mount correctly (centered and slideOver)
 *   2. Nested modals stack (slideOver inside centered, centered inside slideOver)
 *   3. 3+ level deep nesting works (centered → slideOver → centered → slideOver)
 *   4. The topmost modal's heading is visible via assertMountedActionModalSee
 *   5. Unmounting peels one level off the stack
 *
 * Filament v5 renders action modals inside a `wire:partial="action-modals"`
 * container. The partial HTML is returned as part of the Livewire response
 * effects, not in the main `$component->html()`. We use Filament's
 * `assertMountedActionModalSee/SeeHtml` which reads from the correct partial.
 */
class NestedModalLivewireTest extends TestCase
{
    // ─── Single modal tests ─────────────────────────────────────────────

    #[Test]
    public function can_mount_centered_action(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction('centered')
            ->assertActionMounted('centered');
    }

    #[Test]
    public function centered_modal_shows_heading(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction('centered')
            ->assertMountedActionModalSee('Centered Modal (Level 1)');
    }

    #[Test]
    public function can_mount_slide_over_action(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction('slideOver')
            ->assertActionMounted('slideOver');
    }

    #[Test]
    public function slide_over_modal_shows_heading(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction('slideOver')
            ->assertMountedActionModalSee('SlideOver Modal (Level 1)');
    }

    #[Test]
    public function slide_over_modal_has_slide_over_class(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction('slideOver')
            ->assertMountedActionModalSeeHtml('fi-modal-slide-over');
    }

    // ─── 2-level nested modal tests ─────────────────────────────────────

    #[Test]
    public function can_mount_centered_then_nested_slide_over(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['centered', 'nestedSlideOver'])
            ->assertActionMounted(['centered', 'nestedSlideOver']);
    }

    #[Test]
    public function nested_slide_over_inside_centered_shows_heading(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['centered', 'nestedSlideOver'])
            ->assertMountedActionModalSee('SlideOver Modal (Level 2)');
    }

    #[Test]
    public function can_mount_slide_over_then_nested_centered(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['slideOver', 'nestedCentered'])
            ->assertActionMounted(['slideOver', 'nestedCentered']);
    }

    #[Test]
    public function nested_centered_inside_slide_over_shows_heading(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['slideOver', 'nestedCentered'])
            ->assertMountedActionModalSee('Nested Centered Modal (Level 2)');
    }

    // ─── 3-level deep nesting tests ─────────────────────────────────────

    #[Test]
    public function can_mount_three_levels_centered_slideover_centered(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['centered', 'nestedSlideOver', 'deepCentered'])
            ->assertActionMounted(['centered', 'nestedSlideOver', 'deepCentered']);
    }

    #[Test]
    public function three_level_nesting_shows_deepest_heading(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['centered', 'nestedSlideOver', 'deepCentered'])
            ->assertMountedActionModalSee('Deep Centered Modal (Level 3)');
    }

    #[Test]
    public function can_mount_three_levels_slideover_centered_slideover(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['slideOver', 'nestedCentered', 'deepSlideOver'])
            ->assertActionMounted(['slideOver', 'nestedCentered', 'deepSlideOver']);
    }

    #[Test]
    public function three_level_reverse_shows_deepest_heading(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['slideOver', 'nestedCentered', 'deepSlideOver'])
            ->assertMountedActionModalSee('Deep SlideOver Modal (Level 3)');
    }

    // ─── 4-level deep nesting (triple nested chain) ─────────────────────

    #[Test]
    public function can_mount_four_levels_centered_slideover_centered_slideover(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['tripleNested', 'tripleLevel2', 'tripleLevel3', 'tripleLevel4'])
            ->assertActionMounted(['tripleNested', 'tripleLevel2', 'tripleLevel3', 'tripleLevel4']);
    }

    #[Test]
    public function four_level_nesting_shows_deepest_heading(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['tripleNested', 'tripleLevel2', 'tripleLevel3', 'tripleLevel4'])
            ->assertMountedActionModalSee('Triple Nested Level 4 (SlideOver)');
    }

    // ─── Unmount tests ──────────────────────────────────────────────────

    #[Test]
    public function unmounting_top_modal_leaves_parent_mounted(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['centered', 'nestedSlideOver'])
            ->unmountAction()
            ->assertActionMounted('centered');
    }

    #[Test]
    public function unmounting_all_modals_clears_stack(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['centered', 'nestedSlideOver'])
            ->unmountAction()   // unmount nestedSlideOver
            ->unmountAction()   // unmount centered
            ->assertActionNotMounted();
    }

    // ─── Modal action exists checks ─────────────────────────────────────

    #[Test]
    public function all_root_actions_exist(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->assertActionExists('centered')
            ->assertActionExists('slideOver')
            ->assertActionExists('tripleNested');
    }

    // ─── Partial-level modal structure assertions ───────────────────────
    // Filament v5 renders each modal in a wire:partial. We verify the
    // partial HTML via assertMountedActionModalSeeHtml so we can assert
    // DOM structure (fi-modal classes, fi-modal-window-ctn, etc.) on the
    // ACTUAL rendered modal HTML, not just the component root.

    #[Test]
    public function centered_modal_partial_has_fi_modal_class(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction('centered')
            ->assertMountedActionModalSeeHtml('fi-modal');
    }

    #[Test]
    public function nested_modal_partial_has_fi_modal_window_ctn(): void
    {
        Livewire::test(NestedModalComponent::class)
            ->mountAction(['centered', 'nestedSlideOver'])
            ->assertMountedActionModalSeeHtml('fi-modal-window-ctn');
    }
}
