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
            Actions\EditAction::make()
                ->action('create')
                ->icon('mw-save')
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
