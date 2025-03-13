<?php

namespace Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\EditCategory;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;

class EditShopCategory extends EditCategory
{
    protected static string $resource = ShopCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
