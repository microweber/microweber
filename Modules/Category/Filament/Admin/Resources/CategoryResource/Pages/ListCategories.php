<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Category\Filament\Admin\Resources\CategoryResource;

class ListCategories extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $view = 'modules.category::admin.filament.mw-categories-list';

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [];
        
        $actions[] = Actions\CreateAction::make();
        
        $multilanguageIsEnabled = true; // TODO
        if ($multilanguageIsEnabled) {
            $actions[] = Actions\LocaleSwitcher::make();
        }
        
        return $actions;
    }
}
