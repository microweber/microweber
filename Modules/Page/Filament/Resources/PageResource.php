<?php

namespace Modules\Page\Filament\Resources;

use App\Filament\Admin\Resources\PageResource\Pages;
use App\Filament\Admin\Resources\PageResource\RelationManagers;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Page\Models\Page;

class PageResource extends ContentResource
{
    protected static ?string $model = Page::class;

 //   protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 1;

    protected static string $contentType = 'page';
    protected static string $subType = 'page';

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Page\Filament\Resources\PageResource\Pages\ListPages::route('/'),
            'create' => \Modules\Page\Filament\Resources\PageResource\Pages\CreatePage::route('/create'),
            'edit' => \Modules\Page\Filament\Resources\PageResource\Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
