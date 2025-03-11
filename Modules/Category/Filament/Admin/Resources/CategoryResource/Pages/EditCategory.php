<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Filament\Admin\Resources\CategoryResource;

class EditCategory extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    
    protected static string $resource = CategoryResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if($this->activeLocale) {
            $data['lang'] = $this->activeLocale;
        }
        
        $record->update($data);
        
        return $record;
    }

    protected function getHeaderActions(): array
    {
        $actions = [];
        
        $actions[] = Actions\DeleteAction::make();
        
        $multilanguageIsEnabled = true; // TODO
        if ($multilanguageIsEnabled) {
            $actions[] = Actions\LocaleSwitcher::make();
        }
        
        return $actions;
    }
}
