<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests;

use Livewire\Livewire;
use MicroweberPackages\LivewireModal\Modal;
use PHPUnit\Framework\Attributes\Test;

/**
 * Nested modal stacking — parent must remain open when a child is opened.
 */
class NestedModalTest extends TestCase
{
    #[Test]
    public function opening_child_keeps_parent_on_stack(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'nested-parent-modal', ['title' => 'Parent']);

        $parentId = $host->get('activeComponent');
        $this->assertNotNull($parentId);

        $host->call('openModal', 'nested-child-modal', ['title' => 'Child']);

        $stack = $host->get('stack');
        $this->assertCount(2, $stack);
        $this->assertSame($parentId, $stack[0]);
        $this->assertSame($host->get('activeComponent'), $stack[1]);
        $this->assertNotSame($parentId, $host->get('activeComponent'));

        // Both instances still present
        $components = $host->get('components');
        $this->assertArrayHasKey($parentId, $components);
        $this->assertArrayHasKey($stack[1], $components);
        $this->assertSame('nested-parent-modal', $components[$parentId]['name']);
        $this->assertSame('nested-child-modal', $components[$stack[1]]['name']);
    }

    #[Test]
    public function closing_child_restores_parent_as_active(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'nested-parent-modal', ['title' => 'Parent'])
            ->call('openModal', 'nested-child-modal', ['title' => 'Child']);

        $parentId = $host->get('stack')[0];
        $childId = $host->get('stack')[1];

        $host->call('closeModal');

        $this->assertSame([$parentId], $host->get('stack'));
        $this->assertSame($parentId, $host->get('activeComponent'));
        $this->assertArrayNotHasKey($childId, $host->get('components'));
        $this->assertArrayHasKey($parentId, $host->get('components'));
    }

    #[Test]
    public function three_level_nesting_pops_one_at_a_time(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'L1'])
            ->call('openModal', 'demo-modal', ['title' => 'L2'])
            ->call('openModal', 'demo-modal', ['title' => 'L3']);

        $this->assertCount(3, $host->get('stack'));

        $host->call('closeModal');
        $this->assertCount(2, $host->get('stack'));

        $host->call('closeModal');
        $this->assertCount(1, $host->get('stack'));

        $host->call('closeModal');
        $this->assertCount(0, $host->get('stack'));
        $this->assertNull($host->get('activeComponent'));
    }

    #[Test]
    public function nested_modals_receive_increasing_z_index(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'L1'])
            ->call('openModal', 'demo-modal', ['title' => 'L2']);

        $stack = $host->get('stack');
        $components = $host->get('components');

        $z1 = $components[$stack[0]]['zIndex'];
        $z2 = $components[$stack[1]]['zIndex'];

        $this->assertGreaterThan($z1, $z2);
    }
}
