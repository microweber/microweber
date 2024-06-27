<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use MicroweberPackages\Filament\Actions\ImportAction;
use MicroweberPackages\Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter;

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
            ->importer(NewsletterSubscriberImporter::class);
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.filament.admin.newsletter-import-subscribers-action');
    }
}
