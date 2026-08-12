<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests;

use Livewire\Livewire;
use MicroweberPackages\LivewireModal\Modal;
use MicroweberPackages\LivewireModal\ModalComponent;
use MicroweberPackages\LivewireModal\Tests\Fixtures\DemoModal;
use PHPUnit\Framework\Attributes\Test;

class ModalTest extends TestCase
{
    #[Test]
    public function modal_host_can_be_mounted(): void
    {
        Livewire::test(Modal::class)
            ->assertStatus(200)
            ->assertSee('modal-holder-livewire', false);
    }

    #[Test]
    public function open_modal_adds_unique_instance_to_stack(): void
    {
        $component = Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'First']);

        $this->assertCount(1, $component->get('components'));
        $this->assertCount(1, $component->get('stack'));
        $this->assertNotNull($component->get('activeComponent'));

        $firstId = $component->get('activeComponent');
        $this->assertStringStartsWith('mwlm_', (string) $firstId);
    }

    #[Test]
    public function multiple_opens_of_same_component_get_different_ids(): void
    {
        $component = Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'Same'])
            ->call('openModal', 'demo-modal', ['title' => 'Same']);

        $components = $component->get('components');
        $stack = $component->get('stack');

        $this->assertCount(2, $components);
        $this->assertCount(2, $stack);
        $this->assertNotSame($stack[0], $stack[1]);
        $this->assertArrayHasKey($stack[0], $components);
        $this->assertArrayHasKey($stack[1], $components);
    }

    #[Test]
    public function close_modal_pops_only_topmost_instance(): void
    {
        $component = Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'A'])
            ->call('openModal', 'demo-modal', ['title' => 'B']);

        $stackBefore = $component->get('stack');
        $this->assertCount(2, $stackBefore);

        $component->call('closeModal');

        $stackAfter = $component->get('stack');
        $this->assertCount(1, $stackAfter);
        $this->assertSame($stackBefore[0], $stackAfter[0]);
        $this->assertSame($stackBefore[0], $component->get('activeComponent'));
    }

    #[Test]
    public function force_close_clears_entire_stack(): void
    {
        $component = Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'A'])
            ->call('openModal', 'demo-modal', ['title' => 'B'])
            ->call('closeModal', true);

        $this->assertSame([], $component->get('components'));
        $this->assertSame([], $component->get('stack'));
        $this->assertNull($component->get('activeComponent'));
    }

    #[Test]
    public function open_modal_rejects_non_modal_component(): void
    {
        \Livewire\Livewire::component('not-a-modal', new class extends \Livewire\Component {
            public function render(): string
            {
                return '<div></div>';
            }
        }::class);

        $this->expectException(\Exception::class);

        Livewire::test(Modal::class)
            ->call('openModal', 'not-a-modal');
    }

    #[Test]
    public function demo_modal_extends_modal_component(): void
    {
        $this->assertInstanceOf(ModalComponent::class, new DemoModal());
    }

    #[Test]
    public function defaults_enable_close_button_backdrop_escape_and_click_away(): void
    {
        $this->assertTrue(DemoModal::showCloseButton());
        $this->assertTrue(DemoModal::showBackdrop());
        $this->assertTrue(DemoModal::closeModalOnEscape());
        $this->assertTrue(DemoModal::closeModalOnClickAway());
    }

    #[Test]
    public function alpine_style_object_dispatch_is_accepted(): void
    {
        $component = Livewire::test(Modal::class)
            ->call('openModal', [
                'component' => 'demo-modal',
                'title' => 'From object',
            ]);

        $this->assertCount(1, $component->get('components'));
        $id = $component->get('activeComponent');
        $this->assertSame('From object', $component->get('components')[$id]['arguments']['title'] ?? null);
    }

    #[Test]
    public function render_includes_default_skin_close_button_markup_when_open(): void
    {
        Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'Skin'])
            ->assertSee('mw-modal-close-x', false)
            ->assertSee('role="dialog"', false)
            ->assertSee('aria-modal="true"', false);
    }
}
