<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageResource\Pages;
use App\Filament\Admin\Resources\PageResource\RelationManagers;
use MicroweberPackages\Page\Models\Page;
use Modules\Content\Filament\Admin\ContentResource;

class PageResource extends ContentResource
{
    protected static ?string $model = Page::class;

 //   protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website';
    protected static bool $shouldRegisterNavigation = true;

    protected static string $contentType = 'page';
    protected static string $subType = 'page';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
