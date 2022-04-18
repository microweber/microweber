<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class HtmlDropdownMappingPreview extends Component
{
    public $data = [];

    public function render()
    {
        return view('import_export_tool::admin.livewire-html-dropdown-mapping-preview');
    }

    public function mount($importFeedId)
    {
        $importFeed = ImportFeed::where('id', $importFeedId)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $contentXml = file_get_contents(base_path() . DS . $importFeed->source_file_realpath);

        $newReader = new XmlToArray();
        $data = $newReader->readXml($contentXml);

        $dropdownMapping = new \MicroweberPackages\Import\ImportMapping\HtmlDropdownMappingPreview();
        $dropdownMapping->setContent($data);
        $dropdownMapping->setContentParentTags($importFeed->content_tag);

        $this->data = $dropdownMapping->render();
    }

}
