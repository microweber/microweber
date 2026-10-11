<?php

namespace Modules\Category\Filament\Admin\Resources;



class ShopCategoryResource extends CategoryResource
{
    protected static string | \UnitEnum | null $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 3;

    // Distinct label/icon so this isn't confused with the Website "Categories".
    protected static ?string $navigationLabel = 'Product Categories';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    // Hidden from the sidebar; reached from the Shop > More hub page.
    protected static bool $shouldRegisterNavigation = false;

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories::route('/'),
            'create' => \Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\CreateShopCategory::route('/create'),
            'edit' => \Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\EditShopCategory::route('/{record}/edit'),
        ];
    }
}
