<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Category\Filament\Admin\Resources\CategoryResource;

class CreateCategory extends CreateRecord
{
    protected static bool $canCreateAnother = false;

    protected static string $resource = CategoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        if(property_exists($this, 'activeLocale') && $this->activeLocale) {
            $data['lang'] = $this->activeLocale;
        }

        return static::getModel()::create($data);
    }
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('saveCategory')
                ->action('create')
                ->icon('heroicon-o-check-circle')
                ->size('xl')
                ->label('Save')
                ->color('primary'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
