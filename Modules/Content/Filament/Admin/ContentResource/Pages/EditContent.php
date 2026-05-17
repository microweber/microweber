<?php

namespace Modules\Content\Filament\Admin\ContentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Actions\DeleteAction;
use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;
use MicroweberPackages\Filament\Concerns\ModifyComponentData;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Content\Concerns\HasEditContentForms;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Models\Content;

class EditContent extends EditRecord
{
    use HasEditContentForms;
    use ModifyComponentData;

 //  public ?string $activeLocale;

//    protected string $view = 'modules.content::filament.admin.edit-record';

    protected static string $resource = ContentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        if(property_exists($this, 'activeLocale') && $this->activeLocale) {
            $data['lang'] = $this->activeLocale;
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


        $record->update($data);

        if (isset($data['is_home']) and $data['is_home']) {
            //unset is_home from other records as there can be only one home
            Content::where('is_home', '=',1)
                ->where('id', '!=', $record->id)
                ->update(['is_home' => 0]);
        }

        return $record;
    }

    use \Filament\Schemas\Concerns\InteractsWithSchemas;

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return static::getResource()::form($schema);
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
            ->color('info')
            ->labeledFrom('md');



        $actions[]  =  DeleteActionOnlyIcon::make()
            ->label('Delete')
            ->icon('heroicon-o-trash')
            ->size('xl')
            ->onlyIconAndTooltip()
            ->outlined();

        $actions[] = $editAction;
        // task-2026-05-17-2b1020 / AI-816 — primary Save CTA must
        // render brand-blue (#0d6efd), not success-green. See
        // CreateContent::getHeaderActions() for the full rationale.
        $actions[] = Actions\EditAction::make()
            ->action('saveContent')
            ->icon('heroicon-o-check-circle')
            ->size('xl')
            ->label('Save')
            ->color('primary')
            ->labeledFrom('md');

        $isMultilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        if ($isMultilanguageEnabled) {
            // $actions[] = Actions\LocaleSwitcher::make();
        }

        return $actions;
    }


}
