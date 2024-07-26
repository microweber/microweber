<?php

namespace MicroweberPackages\Order\Filament\Admin\Resources\OrderResource\Pages;

use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;
use MicroweberPackages\Order\Filament\Admin\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteActionOnlyIcon::make()
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->size('xl')
                ->onlyIconAndTooltip()
                ->outlined(),
            Actions\EditAction::make()
                ->action('save')
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
