<?php

namespace Modules\Content\Filament\Admin\ContentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Concerns\ModifyComponentData;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Content\Concerns\HasEditContentForms;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Models\Content;

class CreateContent extends CreateRecord
{
    use HasEditContentForms;
    use ModifyComponentData;

//    public $activeLocale;

//    protected string $view = 'modules.content::filament.admin.create-record';


    protected static string $resource = ContentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        if ($this->activeLocale) {
            $data['lang'] = $this->activeLocale;
        }


        if (isset($data['content_type']) and ($data['content_type']) == 'page') {
            // Check if there's no homepage set
            $hasHomepage = Content::where('content_type', 'page')
                ->where('is_home', 1)
                ->exists();

            if (!$hasHomepage) {
                $data['is_home'] = 1; // Set this page as homepage

            }
        }

      $the_active_site_template = template_name();

        //check if active_site_template is the default template and if so , unset the template to null
        if (isset($data['active_site_template']) and
            (
                $data['active_site_template'] == 'default'
                or $data ['active_site_template'] == $the_active_site_template
            )
        ) {
            unset($data['active_site_template']);
        }


        $record = static::getModel()::create($data);


        if (isset($data['content_type']) and ($data['content_type']) == 'page') {
            if (isset($data['is_home']) and $data['is_home']) {
                //unset is_home from other records as there can be only one home
                Content::where('is_home', '=', 1)
                    ->where('id', '!=', $record->id)
                    ->update(['is_home' => 0]);

                Content::where('id', '=', $record->id)
                    ->update(['is_home' => 1]);

                $record->is_home = 1;

            }
        }

        return $record;

    }

    use \Filament\Schemas\Concerns\InteractsWithSchemas;

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema($this->getEditContentForms());
    }


    protected function getHeaderActions(): array
    {

        $actions = [];

        $editAction = Actions\EditAction::make()->action('saveContentAndGoLiveEdit');
        if (request()->header('Sec-Fetch-Dest') === 'iframe') {
            $editAction = Actions\EditAction::make()->action('saveContentAndGoLiveEditIframe');
        }

        $editAction->icon('heroicon-m-eye')
            ->label('Live edit')
            ->size('xl')
            ->color('info');

        $actions[] = $editAction;


        $actions[] = Actions\EditAction::make()
            ->action('saveContent')
            ->icon('mw-save')
            ->size('xl')
            ->label('Save')
            ->color('success');


        $isMultilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        if ($isMultilanguageEnabled) {
            // $actions[] = Actions\LocaleSwitcher::make();
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
