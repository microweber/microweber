<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;

class Install extends Component
{
    public $showInstaller = false;

    public function render()
    {
        return view('import_export_tool::admin.livewire-install');
    }

    public function install()
    {
        $this->showInstaller = true;
    }
}
