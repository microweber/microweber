<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Modules\Content\Filament\Admin\ContentResource\Pages\ListContents;
use Modules\Product\Filament\Admin\Resources\ProductResource;


class ListProducts extends ListContents
{
    protected static string $resource = ProductResource::class;
}
