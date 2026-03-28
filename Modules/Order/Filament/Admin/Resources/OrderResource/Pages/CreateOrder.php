<?php

namespace Modules\Order\Filament\Admin\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Modules\Order\Filament\Admin\Resources\OrderResource;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
Actions\Action::make('save')
            ->action('create')
            ->icon('heroicon-o-check-circle')
            ->size('xl')
            ->label('Save')
            ->color('success'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
        ];
    }
}
