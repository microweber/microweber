<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use LivewireUI\Modal\ModalComponent;

class NewImportModal extends ModalComponent
{
    public function render()
    {
        return view('import_export_tool::admin.livewire-new-import-modal');
    }

    public function mount()
    {

    }
}
