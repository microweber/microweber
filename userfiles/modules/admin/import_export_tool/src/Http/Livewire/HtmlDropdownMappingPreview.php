<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class HtmlDropdownMappingPreview extends Component
{
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

        if ($this->isSimplePreview()) {

            $contentKeys = [];
            if (isset($this->import_feed['source_content'][$importFeed->content_tag][0])) {
                foreach ($this->import_feed['source_content'][$importFeed->content_tag][0] as $itemKey => $itemValue) {
                    $contentKeys[$itemKey] = $itemValue;
                }
                $contentKeys = array_filter($contentKeys);
            }

            $this->data = $contentKeys;

        } else {

            unset($this->import_feed['source_content']);

            $dropdownMapping = new HtmlDropdownMappingRecursiveTable();
            $dropdownMapping->setContent($importFeed->source_content);
            $dropdownMapping->setContentParentTags($importFeed->content_tag);
            $dropdownMapping->setImportTo($this->import_feed['import_to']);

            $this->data = $dropdownMapping->render();

            if (empty($this->import_feed['mapped_tags'])) {
                $this->import_feed['mapped_tags'] = $dropdownMapping->getAutomaticSelectedOptions();
            }
        }
    }

    public function render()
    {
        // Save mapped
        if (isset($this->import_feed['mapped_tags']) && is_array($this->import_feed['mapped_tags'])) {

            $importFeed = ImportFeed::where('id', $this->import_feed['id'])->first();
            $importFeed->mapped_tags = $this->import_feed['mapped_tags'];
            $importFeed->save();

            $this->emit('refreshImportFeedStateById', $importFeed->id);
        }

        if ($this->isSimplePreview()) {
            return view('import_export_tool::admin.livewire-html-dropdown-mapping-simple-preview');
        } else {
            return view('import_export_tool::admin.livewire-html-dropdown-mapping-preview');
        }
    }

    private function isSimplePreview()
    {/*
        $fileExt = pathinfo($this->import_feed['source_file_realpath'], PATHINFO_EXTENSION);
        if ($fileExt == 'xlsx' || $fileExt == 'xls' || $fileExt == 'csv') {
            return true;
        }
*/
        return false;
    }

    public function mount($importFeedId)
    {
        $this->import_feed_id = $importFeedId;
        $this->readFeed();
    }

}
