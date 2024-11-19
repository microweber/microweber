<?php

namespace Modules\Post\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Post\Models\Post;

class PostResource extends ContentResource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Website';

    protected static bool $shouldRegisterNavigation = true;

    protected static string $contentType = 'post';
    protected static string $subType = 'post';

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts::route('/'),
            'create' => \Modules\Post\Filament\Admin\Resources\PostResource\Pages\CreatePost::route('/create'),
            'edit' => \Modules\Post\Filament\Admin\Resources\PostResource\Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
