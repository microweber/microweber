<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\ContentResource\Pages\EditContent;
use App\Filament\Admin\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditContent
{
    protected static string $resource = PageResource::class;


}
