<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class DropdownMappingPreview extends Component
{
    use HtmlDropdownMappingRecursiveTable;

    public $import_feed_id = 0;
    public $import_feed = [];
    public $data;
    public $listeners = [
        'htmlDropdownMappingPreviewRefresh'=>'readFeed'
    ];

    public function readFeed()
    {
        $importFeed = ImportFeed::where('id', $this->import_feed_id)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $this->import_feed = $importFeed->toArray();

        unset($this->import_feed['source_content']);

        $this->setContent($importFeed->source_content);
        $this->setContentParentTags($importFeed->content_tag);
        $this->setImportTo($this->import_feed['import_to']);

        if (empty($this->import_feed['mapped_tags'])) {
            $this->import_feed['mapped_tags'] = $this->getAutomaticSelectedOptions();
        }

    }

    public function mount($importFeedId)
    {
        $this->import_feed_id = $importFeedId;
        $this->readFeed();
    }

}
