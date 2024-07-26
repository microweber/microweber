<?php

namespace MicroweberPackages\Order\Filament\Admin\Resources\OrderResource\Pages;

use MicroweberPackages\Order\Filament\Admin\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->action('save')
                ->icon('mw-save')
                ->size('xl')
                ->label('Save')
                ->color('success'),
            Actions\DeleteAction::make()
            ->size('xl'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
        ];
    }
}
