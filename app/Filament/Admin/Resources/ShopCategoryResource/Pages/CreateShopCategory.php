<?php

namespace App\Filament\Admin\Resources\ShopCategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;

class CreateShopCategory extends CreateRecord
{
    protected static string $resource = ShopCategoryResource::class;
}
