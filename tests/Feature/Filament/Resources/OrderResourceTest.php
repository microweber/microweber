<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Order\Filament\Admin\Resources\OrderResource;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class OrderResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return OrderResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListOrders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(OrderResource::getModel());
    }

    #[Test]
    public function it_resource_has_navigation_badge(): void
    {
        $this->actingAsAdmin();

        $badge = OrderResource::getNavigationBadge();
        $this->assertTrue($badge === null || is_string($badge));
    }
}
