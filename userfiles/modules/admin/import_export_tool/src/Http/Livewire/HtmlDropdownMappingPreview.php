<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class HtmlDropdownMappingPreview extends Component
{
    public $import_feed = [];
    public $data;

    public function render()
    {
        if (isset($this->import_feed['mapped_tags']) && is_array($this->import_feed['mapped_tags'])) {
            $importFeed = ImportFeed::where('id', $this->import_feed['id'])->first();
            $importFeed->mapped_tags = $this->import_feed['mapped_tags'];
            $importFeed->save();
        }

        return view('import_export_tool::admin.livewire-html-dropdown-mapping-preview');
    }

    public function mount($importFeedId)
    {
        $importFeed = ImportFeed::where('id', $importFeedId)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }
        $this->import_feed = $importFeed->toArray();

        $xmlFile = base_path() . DS . $importFeed->source_file_realpath;
        if (!is_file($xmlFile)) {
            return;
        }

        $contentXml = file_get_contents($xmlFile);

        $newReader = new XmlToArray();
        $data = $newReader->readXml($contentXml);

        $dropdownMapping = new \MicroweberPackages\Import\ImportMapping\HtmlDropdownMappingPreview();
        $dropdownMapping->setContent($data);
        $dropdownMapping->setContentParentTags($importFeed->content_tag);

        $this->data = $dropdownMapping->render();

        if (empty($this->import_feed['mapped_tags'])) {
            $this->import_feed['mapped_tags'] = $dropdownMapping->getAutomaticSelectedOptions();
        }
    }

}
