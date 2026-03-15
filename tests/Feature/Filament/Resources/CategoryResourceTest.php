<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

#[RunTestsInSeparateProcesses]
class CategoryResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return CategoryResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListCategories::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(CategoryResource::getModel());
    }
}
