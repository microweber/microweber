<?php

namespace Modules\Content\Filament\Admin\ContentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Concerns\ModifyComponentData;
use Modules\Content\Concerns\HasEditContentForms;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Models\Content;

class CreateContent extends CreateRecord
{

    use CreateRecord\Concerns\Translatable;
    use HasEditContentForms;
    use ModifyComponentData;

//    public $activeLocale;

//    protected static string $view = 'modules.content::filament.admin.create-record';


    protected static string $resource = ContentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record =  static::getModel()::create($data);


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

        $actions = [];

        $editAction =  Actions\EditAction::make()->action('saveContentAndGoLiveEdit');
        if (request()->header('Sec-Fetch-Dest') === 'iframe') {
            $editAction =  Actions\EditAction::make()->action('saveContentAndGoLiveEditIframe');
        }

        $editAction->icon('heroicon-m-eye')
            ->label('Live edit')
            ->size('xl')
            ->color('info');

        $actions[] = $editAction;


        $actions[] =  Actions\EditAction::make()
                ->action('saveContent')
                ->icon('mw-save')
                ->size('xl')
                ->label('Save')
                ->color('success');


        $isMultilanguageEnabled = true; // TODO
        if ($isMultilanguageEnabled) {
            $actions[] =  Actions\LocaleSwitcher::make();
        }

        return $actions;
    }

    protected function getFormActions(): array
    {
        return [
            //   Actions\CreateAction::make()->action('saveContent')->label('Save')->color('success'),

        ];
    }

}
