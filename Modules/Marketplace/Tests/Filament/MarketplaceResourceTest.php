<?php

namespace Modules\Marketplace\Tests\Filament;

use Livewire\Livewire;
use Modules\Marketplace\Filament\Admin\MarketplaceResource;
use Modules\Marketplace\Filament\Admin\MarketplaceResource\Pages\ListMarketplaces;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class MarketplaceResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return MarketplaceResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListMarketplaces::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(MarketplaceResource::getModel());
    }
}
