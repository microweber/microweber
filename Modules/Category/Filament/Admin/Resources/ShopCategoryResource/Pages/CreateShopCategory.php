<?php

namespace Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;

class CreateShopCategory extends CreateCategory
{
    protected static string $resource = ShopCategoryResource::class;
}
