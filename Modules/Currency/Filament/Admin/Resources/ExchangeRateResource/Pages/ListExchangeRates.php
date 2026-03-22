<?php

namespace Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Currency\Filament\Admin\Resources\ExchangeRateResource;
use Modules\Currency\Services\CurrencyConversionService;

class ListExchangeRates extends ListRecords
{
    protected static string $resource = ExchangeRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('clearCache')
                ->label('Clear Rate Cache')
                ->icon('heroicon-o-trash')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Clear Exchange Rate Cache')
                ->modalDescription('This will clear all cached exchange rates and reload from the database.')
                ->action(function () {
                    app(CurrencyConversionService::class)->clearCache();
                    \Filament\Notifications\Notification::make()
                        ->title('Cache Cleared')
                        ->body('Exchange rate cache has been cleared successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
