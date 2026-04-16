<?php

namespace Modules\Order\Tests\Filament;

use Livewire\Livewire;
use Modules\Order\Filament\Admin\Resources\OrderResource;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return OrderResource::class;
    }

    #[Test]
    public function it_can_render_orders_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListOrders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_orders(): void
    {
        $this->actingAsUser();

        $response = $this->get(OrderResource::getUrl('index'));
        $response->assertForbidden();
    }
}
