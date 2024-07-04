<?php

namespace App\Filament\Admin\Resources\ContentResource\Pages;

use App\Filament\Admin\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Pages\CreateRecord;
use MicroweberPackages\Content\Concerns\HasEditContentForms;
use Livewire\Attributes\On;
use MicroweberPackages\Filament\Concerns\ModifyComponentData;

class CreateContent extends CreateRecord
{

    use Translatable;
    use HasEditContentForms;
    use ModifyComponentData;

    public $activeLocale;

    protected static string $view = 'content::admin.content.filament.create-record';


    protected static string $resource = ContentResource::class;


    protected function getForms(): array
    {
        return $this->getEditContentForms();
    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->action('saveContent')->label('Save')->color('success'),
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            //   Actions\CreateAction::make()->action('saveContent')->label('Save')->color('success'),

        ];
    }

}
