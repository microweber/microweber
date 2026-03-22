<?php

namespace Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Currency\Filament\Admin\Resources\ExchangeRateResource;
use Modules\Currency\Services\CurrencyConversionService;

class EditExchangeRate extends EditRecord
{
    protected static string $resource = ExchangeRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache for this currency pair
        app(CurrencyConversionService::class)->clearCache(
            $this->record->from_currency,
            $this->record->to_currency
        );
        
        \Filament\Notifications\Notification::make()
            ->title('Exchange Rate Updated')
            ->body("Exchange rate from {$this->record->from_currency} to {$this->record->to_currency} has been updated.")
            ->success()
            ->send();
    }
}
