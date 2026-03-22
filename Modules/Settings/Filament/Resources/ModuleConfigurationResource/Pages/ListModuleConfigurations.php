<?php

namespace Modules\Settings\Filament\Resources\ModuleConfigurationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Settings\Filament\Resources\ModuleConfigurationResource;

class ListModuleConfigurations extends ListRecords
{
    protected static string $resource = ModuleConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Modules are managed via marketplace, so no create action needed
        ];
    }

    public function getTitle(): string
    {
        return 'Module Configuration';
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('admin') => 'Dashboard',
            '#' => 'Module Configuration',
        ];
    }
}
