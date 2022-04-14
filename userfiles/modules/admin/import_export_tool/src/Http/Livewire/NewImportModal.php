<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class NewImportModal extends ModalComponent
{
    public $new_feed_name;

    public function save()
    {
        $feed = new ImportFeed();
        $feed->name = $this->new_feed_name;
        $feed->save();

        $this->closeModal();

        return $feed->id;
    }

    public function render()
    {
        return view('import_export_tool::admin.livewire-new-import-modal');
    }
}
