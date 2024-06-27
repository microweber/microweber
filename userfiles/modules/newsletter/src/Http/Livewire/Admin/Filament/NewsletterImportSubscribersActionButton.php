<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use MicroweberPackages\Filament\Actions\ImportAction;
use MicroweberPackages\Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;

class NewsletterImportSubscribersActionButton extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function importAction(): ImportAction
    {
        return ImportAction::make('import')
            ->label('Import Subscribers')
            ->icon('heroicon-m-cloud-arrow-up')
            ->size('xl')
            ->afterImport(function ($action, $data) {
                $importedData = $action->getImportedData();
                if (isset($importedData['importOptions']['new_list_name'])) {
                    $findList = NewsletterList::where('name', $importedData['importOptions']['new_list_name'])->first();
                    if ($findList) {
                        $this->dispatch('subscribers-imported', listId: $findList->id);
                        return;
                    }
                }
                if (isset($importedData['importOptions']['lists'][0])) {
                    $this->dispatch('subscribers-imported', listId: $importedData['importOptions']['lists'][0]);
                    return;
                }

                $this->dispatch('subscribers-imported', listId: null);
            })
            ->importer(NewsletterSubscriberImporter::class);
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.filament.admin.newsletter-import-subscribers-action');
    }
}
