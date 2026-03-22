<?php

namespace Modules\Settings\Filament\Resources\ModuleConfigurationResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Module\ModuleManager;
use Modules\Settings\Filament\Resources\ModuleConfigurationResource;

class EditModuleConfiguration extends EditRecord
{
    protected static string $resource = ModuleConfigurationResource::class;

    public function getTitle(): string
    {
        return 'Configure ' . ($this->record->getName() ?? 'Module');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Add any specific header actions here
        ];
    }

    protected function afterSave(): void
    {
        // Clear module cache after settings are saved
        Cache::forget('modules');

        Notification::make()
            ->title('Module configuration saved')
            ->success()
            ->send();
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('admin/module-configuration') => 'Module Configuration',
            '#' => 'Configure',
        ];
    }
}
