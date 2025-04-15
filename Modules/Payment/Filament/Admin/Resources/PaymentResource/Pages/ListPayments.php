<?php

namespace Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('settings')
                ->label('Settings')
                ->url(PaymentProviderResource::getUrl('index'))
                ->icon('heroicon-o-cog-6-tooth'),
        ];
    }
}
