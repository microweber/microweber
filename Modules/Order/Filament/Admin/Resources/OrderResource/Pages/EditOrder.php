<?php

namespace Modules\Order\Filament\Admin\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;
use Modules\Order\Filament\Admin\Resources\OrderResource;

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
