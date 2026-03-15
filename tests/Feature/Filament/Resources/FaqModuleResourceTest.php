<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Faq\Filament\Resources\FaqModuleResource;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages\ListFaqs;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

#[RunTestsInSeparateProcesses]
class FaqModuleResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return FaqModuleResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListFaqs::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(FaqModuleResource::getModel());
    }
}
