<?php

namespace Modules\Post\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Post\Models\Post;

class PostResource extends ContentResource
{
    protected static ?string $model = Post::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Website';
    protected static ?int $navigationSort = 3;

    // task-2026-05-16-008d91 / AI-731 — article-themed icon so
    // Posts surfaces in the admin left-nav under Website. The
    // dispatch flagged that /admin/posts was reachable only via
    // Live Edit redirect or direct URL; the inherited
    // shouldRegisterNavigation override on this class was already
    // true and navigationGroup was already "Website", but the
    // resource carried no icon — Filament v5 suppresses nav items
    // whose icon resolves to null in certain panel themes.
    // heroicon-o-newspaper is the article-themed glyph (matches
    // "Articles, news, and updates you publish appear here." from
    // the AI-729 empty-state explainer copy).
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

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
