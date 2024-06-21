<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageResource\Pages;
use App\Filament\Admin\Resources\PageResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;

class PageResource extends ContentResource
{
    protected static ?string $model = Page::class;

 //   protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website';
    protected static bool $shouldRegisterNavigation = true;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
