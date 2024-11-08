<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Modules\Content\Filament\Admin\ContentResource\Pages\ListContents;


class ListProducts extends ListContents
{
    protected static string $resource = ProductResource::class;
}
