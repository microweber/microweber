<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Settings\Filament\Resources\TranslationResource;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\ListTranslations;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class TranslationResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return TranslationResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListTranslations::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(TranslationResource::getModel());
    }
}
