<?php

namespace Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;

class ListShopCategories extends ListCategories
{
    protected static string $resource = ShopCategoryResource::class;


}
