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

    /**
     * NOVICE #12 (task-2026-05-13-899d57) — accept the user's
     * already-typed title/body/excerpt from the query string when
     * the Live Edit compact modal opens this Create page via the
     * "Show all options" footer action. The Live Edit modal's JS
     * (iframe-page.blade.php `attachOpenInAdminTitleSync`) appends
     * the current typed values to the button's href on every
     * input event; this method reads them back so the full-form
     * Create page lands with the user's work already filled in
     * instead of a blank form.
     *
     * Filament's `CreateRecord::getInitialData()` returns `[]` by
     * default; overriding it is the canonical hook for pre-filling
     * fields without touching the form schema. Length caps mirror
     * the JS side (title 256, content_body 6 KB, description 2 KB)
     * — defence-in-depth so a hand-crafted URL can't pre-fill a
     * giant body and bloat session memory.
     */
    protected function getInitialData(): array
    {
        $defaults = parent::getInitialData();
        $caps = [
            'title' => 256,
            'content_body' => 6144,
            'description' => 2048,
        ];
        foreach ($caps as $key => $cap) {
            $value = request()->query($key);
            if (is_string($value) && trim($value) !== '') {
                $defaults[$key] = mb_substr($value, 0, $cap);
            }
        }
        return $defaults;
    }

    protected function handleRecordCreation(array $data): Model
    {
        if (property_exists($this, 'activeLocale') && $this->activeLocale) {
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
        return static::getResource()::form($schema);
    }


    protected function getHeaderActions(): array
    {

        $actions = [];

        $editAction = Actions\Action::make('liveEdit')->action('saveContentAndGoLiveEdit');
        if (request()->header('Sec-Fetch-Dest') === 'iframe') {
            $editAction = Actions\Action::make('liveEditIframe')->action('saveContentAndGoLiveEditIframe');
        }

        $editAction->icon('heroicon-m-eye')
            ->label('Live edit')
            ->size('xl')
            ->color('info');

        $actions[] = $editAction;


        // task-2026-05-17-2b1020 / AI-816 — Save CTA is the primary
        // unsubmitted-form action, so it must render brand-blue
        // (#0d6efd / Color::Primary), NOT success-green (#2FB344).
        // Green semantically reads as "saved successfully" — using
        // it on an UNSUBMITTED form conflates the click target with
        // the post-submit success state. Same lineage as AI-737 /
        // AI-704 / AI-731 / AI-794 brand-blue-for-primary unification.
        $actions[] = Actions\Action::make('saveContent')
            ->action('saveContent')
            ->icon('heroicon-o-check-circle')
            ->size('xl')
            ->label('Save')
            ->color('primary');


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
