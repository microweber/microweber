<?php

namespace Modules\Content\Filament\Admin\ContentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use MicroweberPackages\Multilanguage\Filament\Pages\ListRecords\Concerns\TranslatableRecordsList;
use Modules\Content\Filament\Admin\ContentResource;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    use HasToggleableTable;
    use TranslatableRecordsList;


    public function getDefaultLayoutView(): string
    {
        return 'grid';
    }

    protected function getHeaderActions(): array
    {

        $actions = [];
        $actions[] =
            Actions\CreateAction::make()
                ->size('xl')
                ->icon('heroicon-o-plus')
                ->color('mw-secondary');


        $multilanguageIsEnabled = true; // TODO
        if ($multilanguageIsEnabled) {
            $actions[] =  Actions\LocaleSwitcher::make();
        }

        return $actions;
    }


}
