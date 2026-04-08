<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Category\Filament\Admin\Resources\CategoryResource;

class EditCategory extends EditRecord
{

    protected static string $resource = CategoryResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if(property_exists($this, 'activeLocale') && $this->activeLocale) {
            $data['lang'] = $this->activeLocale;
        }

        $record->update($data);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteActionOnlyIcon::make()
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->size('xl')
                ->onlyIconAndTooltip()
                ->outlined(),

            Actions\Action::make('saveCategory')
                ->action('save')
                ->icon('heroicon-o-check-circle')
                ->size('xl')
                ->label('Save')
                ->color('success'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
