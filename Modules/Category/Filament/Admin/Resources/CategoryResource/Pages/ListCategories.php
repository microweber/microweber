<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\Multilanguage\Filament\Pages\ListRecords\Concerns\TranslatableRecordsList;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Multilanguage\Filament\Pages\Multilanguage;

class ListCategories extends ListRecords
{
    use  TranslatableRecordsList;

    protected string $view = 'modules.category::admin.filament.mw-categories-list';

    protected static string $resource = CategoryResource::class;

    public function updatedActiveLocale()
    {
        $this->dispatch('treeLanguageChanged', locale: $this->activeLocale);


    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        $actions[] = Actions\CreateAction::make();

        $isMultilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        if ($isMultilanguageEnabled) {
            // $actions[] = Actions\LocaleSwitcher::make();
        }

        return $actions;
    }
}
