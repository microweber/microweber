<?php

namespace Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Currency\Filament\Admin\Resources\CurrencyResource;
use Modules\Currency\Services\CurrencyManager;

class ListCurrencies extends ListRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('refreshCache')
                ->label('Refresh Cache')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Refresh Currency Cache')
                ->modalDescription('This will clear the currency cache and reload all active currencies.')
                ->action(function () {
                    app(CurrencyManager::class)->clearCache();
                    \Filament\Notifications\Notification::make()
                        ->title('Cache Cleared')
                        ->body('Currency cache has been refreshed successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
