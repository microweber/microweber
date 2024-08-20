<?php

namespace App\Filament\Admin\Resources\ContentResource\Pages;

use App\Filament\Admin\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use MicroweberPackages\Content\Concerns\HasEditContentForms;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Filament\Actions\DeleteAction;
use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;
use MicroweberPackages\Filament\Concerns\ModifyComponentData;

class EditContent extends EditRecord
{
    use Translatable;
    use HasEditContentForms;
    use ModifyComponentData;

    public $activeLocale;


//    protected static string $view = 'content::admin.content.filament.edit-record';

    protected static string $resource = ContentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
         $record->update($data);

        if (isset($data['is_home']) and $data['is_home']) {
            //unset is_home from other records as there can be only one home
            Content::where('is_home', 1)->where('id', '!=', $record->id)->update(['is_home' => 0]);
        }

        return $record;
    }

    protected function getForms(): array
    {
        return $this->getEditContentForms();
    }


    protected function getHeaderActions(): array
    {


        $editAction =  Actions\EditAction::make()->action('saveContentAndGoLiveEdit');
        if (request()->header('Sec-Fetch-Dest') === 'iframe') {
            $editAction =  Actions\EditAction::make()->action('saveContentAndGoLiveEditIframe');
        }

        $editAction->icon('heroicon-m-eye')
            ->label('Live edit')
            ->size('xl')
            ->color('info');





        return [
            //   Actions\LocaleSwitcher::make(),
            //   Actions\DeleteAction::make()
            DeleteActionOnlyIcon::make()
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->size('xl')
                ->onlyIconAndTooltip()
                ->outlined(),

            $editAction,

            Actions\EditAction::make()
                ->action('saveContent')
                ->icon('mw-save')
                ->size('xl')
                ->label('Save')
                ->color('success'),
        ];
    }


}
