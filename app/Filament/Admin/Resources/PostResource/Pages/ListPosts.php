<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Filament\Admin\Resources\ContentResource\Pages\ListContents;
use App\Filament\Admin\Resources\PostResource;

class ListPosts extends ListContents
{
    protected static string $resource = PostResource::class;
}
