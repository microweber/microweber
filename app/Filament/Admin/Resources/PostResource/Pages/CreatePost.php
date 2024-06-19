<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Filament\Admin\Resources\ContentResource\Pages\CreateContent;
use App\Filament\Admin\Resources\PostResource;

class CreatePost extends CreateContent
{
    protected static string $resource = PostResource::class;
}
