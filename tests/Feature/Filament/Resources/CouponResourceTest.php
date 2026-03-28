<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Coupons\Filament\Resources\CouponResource;
use Modules\Coupons\Filament\Resources\CouponResource\Pages\ListCoupons;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class CouponResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return CouponResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListCoupons::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(CouponResource::getModel());
    }
}
