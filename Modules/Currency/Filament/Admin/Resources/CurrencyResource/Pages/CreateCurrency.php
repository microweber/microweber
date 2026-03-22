<?php

namespace Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Currency\Filament\Admin\Resources\CurrencyResource;
use Modules\Currency\Services\CurrencyManager;

class CreateCurrency extends CreateRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function afterCreate(): void
    {
        // Clear currency cache after creating a new currency
        app(CurrencyManager::class)->clearCache();
        
        \Filament\Notifications\Notification::make()
            ->title('Currency Created')
            ->body('The currency has been created successfully.')
            ->success()
            ->send();
    }
}
