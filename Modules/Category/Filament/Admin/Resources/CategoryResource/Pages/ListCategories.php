<?php

namespace Modules\Category\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\Multilanguage\Filament\Pages\ListRecords\Concerns\TranslatableRecordsList;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Multilanguage\Filament\Pages\Multilanguage;

class ListCategories extends ListRecords
{
    use  TranslatableRecordsList;

    protected static string $view = 'modules.category::admin.filament.mw-categories-list';

    protected static string $resource = CategoryResource::class;

    public function updatedActiveLocale()
    {
        $this->dispatch('treeLanguageChanged', locale: $this->activeLocale);


    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        $actions[] = Actions\CreateAction::make();

        $multilanguageIsEnabled = \MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled();
        if ($multilanguageIsEnabled) {
            $actions[] = Actions\LocaleSwitcher::make();
        }

        return $actions;
    }
}
