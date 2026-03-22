<?php

namespace Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Currency\Filament\Admin\Resources\ExchangeRateResource;
use Modules\Currency\Services\CurrencyConversionService;

class CreateExchangeRate extends CreateRecord
{
    protected static string $resource = ExchangeRateResource::class;

    protected function afterCreate(): void
    {
        // Clear cache for this currency pair
        app(CurrencyConversionService::class)->clearCache(
            $this->record->from_currency,
            $this->record->to_currency
        );
        
        \Filament\Notifications\Notification::make()
            ->title('Exchange Rate Created')
            ->body("Exchange rate from {$this->record->from_currency} to {$this->record->to_currency} has been created.")
            ->success()
            ->send();
    }
}
