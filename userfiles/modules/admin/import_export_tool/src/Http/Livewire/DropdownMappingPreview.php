<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class DropdownMappingPreview extends Component
{
    use HtmlDropdownMappingRecursiveTable;

    public $supported_languages = 0;
    public $import_feed_id = 0;
    public $import_feed = [];
    public $data;
    public $listeners = [
      //  'dropdownMappingPreviewRefresh'=>'$refresh'
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

    }

    public function mount($importFeedId)
    {
        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            $supportedLanguages = get_supported_languages();
            if (!empty($supportedLanguages)) {
                $this->supported_languages = $supportedLanguages;
            }
        }

        $this->import_feed_id = $importFeedId;
        $this->readFeed();
    }

}
