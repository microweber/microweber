<?php

namespace App\Filament\Admin\Resources\ContentResource\Pages;

use App\Filament\Admin\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use MicroweberPackages\Content\Concerns\HasEditContentForms;
use MicroweberPackages\Filament\Concerns\ModifyComponentData;

class EditContent extends EditRecord
{
    use Translatable;
    use HasEditContentForms;
    use ModifyComponentData;

    public $activeLocale;



    protected static string $view = 'content::admin.content.filament.edit-record';

    protected static string $resource = ContentResource::class;


    protected function getForms(): array
    {
        return $this->getEditContentForms();
    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make()->outlined(),
            Actions\EditAction::make()->action('saveContent')->label('Save')->color('success'),
        ];
    }


}
