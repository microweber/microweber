<?php

namespace App\Filament\Admin\Resources\ContentResource\Pages;

use App\Filament\Admin\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use MicroweberPackages\Content\Concerns\HasEditContentForms;
use MicroweberPackages\Filament\Actions\DeleteAction;
use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;
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
            //   Actions\LocaleSwitcher::make(),
            //   Actions\DeleteAction::make()
            DeleteActionOnlyIcon::make()
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->size('xl')
                ->onlyIconAndTooltip()
                ->outlined(),

            Actions\EditAction::make()->action('saveContentAndGoLiveEdit')
                // ->icon('heroicon-o-pencil')
                ->icon('heroicon-o-pencil')
                ->label('Live edit')
                ->size('xl')
                ->color('info'),

            Actions\EditAction::make()
                ->action('saveContent')
                ->icon('heroicon-m-eye')
                ->size('xl')
                ->label('Save')
                ->color('success'),
        ];
    }


}
