<?php

namespace App\Filament\Admin\Resources\ShopCategoryResource\Pages;

use App\Filament\Admin\Resources\ShopCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShopCategories extends ListRecords
{
    protected static string $resource = ShopCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
