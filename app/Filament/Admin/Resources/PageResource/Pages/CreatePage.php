<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\ContentResource\Pages\CreateContent;
use App\Filament\Admin\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateContent
{
    protected static string $resource = PageResource::class;
}
