<?php

namespace Modules\Page\Filament\Resources\PageResource\Pages;

use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;
use Modules\Page\Filament\Resources\PageResource;

class CreatePage extends CreateContent
{
    protected static string $resource = PageResource::class;
}
