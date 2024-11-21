<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Category\Filament\Admin\Resources\CategoryResource;

class ListCategories extends ListRecords
{

    protected static string $view = 'category::admin.filament.mw-categories-list';


    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
