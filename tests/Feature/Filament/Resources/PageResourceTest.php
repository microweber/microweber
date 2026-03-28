<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Page\Filament\Resources\PageResource;
use Modules\Page\Filament\Resources\PageResource\Pages\ListPages;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class PageResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return PageResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListPages::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(PageResource::getModel());
    }

    #[Test]
    public function it_resource_has_navigation_items(): void
    {
        $this->actingAsAdmin();

        $this->assertNotEmpty(PageResource::getNavigationItems());
    }
}
