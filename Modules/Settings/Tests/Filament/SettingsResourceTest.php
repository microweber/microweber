<?php

namespace Modules\Settings\Tests\Filament;

use Livewire\Livewire;
use Modules\Settings\Filament\Resources\ModuleConfigurationResource;
use Modules\Settings\Filament\Resources\ModuleConfigurationResource\Pages\ListModuleConfigurations;
use Modules\Settings\Filament\Resources\TranslationResource;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\ListTranslations;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class SettingsResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return ModuleConfigurationResource::class;
    }

    #[Test]
    public function it_can_render_module_configurations_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListModuleConfigurations::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_translations_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListTranslations::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_settings(): void
    {
        $this->actingAsUser();

        $response = $this->get(ModuleConfigurationResource::getUrl('index'));
        $response->assertForbidden();
    }
}
