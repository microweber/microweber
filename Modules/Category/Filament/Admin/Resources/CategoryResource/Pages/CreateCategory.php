<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Filament\Admin\Resources\CategoryResource;

class CreateCategory extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    
    protected static string $resource = CategoryResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        if($this->activeLocale) {
            $data['lang'] = $this->activeLocale;
        }
        
        return static::getModel()::create($data);
    }
    
    protected function getHeaderActions(): array
    {
        $actions = [];
        
        $multilanguageIsEnabled = true; // TODO
        if ($multilanguageIsEnabled) {
            $actions[] = Actions\LocaleSwitcher::make();
        }
        
        return $actions;
    }
}
