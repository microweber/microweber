<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ContentResource\Pages\ListContents;
use App\Filament\Admin\Resources\ProductResource;


class ListProducts extends ListContents
{
    protected static string $resource = ProductResource::class;
}
