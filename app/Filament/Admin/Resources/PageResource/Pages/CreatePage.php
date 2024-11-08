<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\PageResource;
use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;

class CreatePage extends CreateContent
{
    protected static string $resource = PageResource::class;
}
