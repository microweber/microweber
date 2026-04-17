<?php

namespace Modules\Form\Tests\Filament;

use Livewire\Livewire;
use Modules\Form\Filament\Resources\FormEntryResource;
use Modules\Form\Filament\Resources\FormEntryResource\Pages\ListFormEntries;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class FormEntryResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return FormEntryResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListFormEntries::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(FormEntryResource::getModel());
    }
}
