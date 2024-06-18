<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ContentTabPosition;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ProductResource::class;


    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
