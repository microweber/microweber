<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;

class CreateProduct extends CreateContent
{
    protected static string $resource = ProductResource::class;
}
