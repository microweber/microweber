<?php

namespace Modules\Content\Filament\Admin\ContentResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use MicroweberPackages\Multilanguage\Filament\Pages\ListRecords\Concerns\TranslatableRecordsList;
use Modules\Content\Filament\Admin\ContentResource;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    use HasToggleableTable;
    use TranslatableRecordsList;


//    public function render(): \Illuminate\Contracts\View\View
//    {
//
//
//        return parent::render();
//    }

    public function getDefaultLayoutView(): string
    {
        return 'grid';
    }

    protected function getHeaderActions(): array
    {

        // TASK-021 / TICKET-N / AI-39 (cycle-61 2026-05-08): the
        // in-resource header CreateAction is removed — duplicates the
        // global "+ Add New" dropdown that lives in the admin topbar
        // (`src/MicroweberPackages/Admin/resources/views/layouts/
        // partials/topbar.blade.php` line ~67) and is rendered on
        // every Filament admin page. The global dropdown also offers
        // a richer context-aware choice list (Page / Post / Category
        // / Product) which the bare in-resource CreateAction cannot.
        // Single create-new affordance per the AC.
        $actions = [];

        $multilanguageIsEnabled = true; // TODO
        if ($multilanguageIsEnabled) {
            // $actions[] = Actions\LocaleSwitcher::make();
        }

        return $actions;
    }


}
