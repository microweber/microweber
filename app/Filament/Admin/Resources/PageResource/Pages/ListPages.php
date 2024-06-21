<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\ContentResource\Pages\ListContents;
use App\Filament\Admin\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;

class ListPages extends ListContents
{


    protected static string $resource = PageResource::class;


}
