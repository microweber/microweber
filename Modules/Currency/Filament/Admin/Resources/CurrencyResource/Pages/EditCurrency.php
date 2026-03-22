<?php

namespace Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Currency\Filament\Admin\Resources\CurrencyResource;
use Modules\Currency\Services\CurrencyManager;

class EditCurrency extends EditRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    if ($record->is_default) {
                        \Filament\Notifications\Notification::make()
                            ->title('Cannot Delete Default Currency')
                            ->body('Please set another currency as default before deleting this one.')
                            ->danger()
                            ->send();
                        return false;
                    }
                }),
        ];
    }

    protected function afterSave(): void
    {
        // Clear currency cache after updating
        app(CurrencyManager::class)->clearCache();
        
        \Filament\Notifications\Notification::make()
            ->title('Currency Updated')
            ->body('The currency has been updated successfully.')
            ->success()
            ->send();
    }
}
