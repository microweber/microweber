<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;

class ListProducts extends ListRecords
{
    use HasToggleableTable;
    use ListRecords\Concerns\Translatable;

    public function getLayout(): string
    {
        if (filament()->getCurrentPanel()->getId() == 'admin-live-edit') {
            return 'filament-panels::components.layout.live-edit';
        }
        return parent::getLayout();
    }

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
