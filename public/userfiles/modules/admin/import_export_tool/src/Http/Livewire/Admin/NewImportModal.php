<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class NewImportModal extends AdminModalComponent
{
    public $new_feed_name;

    public function save()
    {
        $feed = new ImportFeed();
        $feed->name = $this->new_feed_name;
        $feed->save();

        return redirect(route('admin.import-export-tool.import', $feed->id));
    }

    public function render()
    {
        return view('import_export_tool::admin.livewire-new-import-modal');
    }
}
