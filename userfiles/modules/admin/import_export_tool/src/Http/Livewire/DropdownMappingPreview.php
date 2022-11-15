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
    
    public $data;
    public $import_feed_id = 0;
    public $import_feed = [];
    public $supported_languages = 0;
    public $listeners = [
        //  'dropdownMappingPreviewRefresh'=>'$refresh'
    ];

    public function mount($importFeedId)
    {
        $importFeed = ImportFeed::where('id', $importFeedId)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $this->import_feed_id = $importFeedId;
        $this->import_feed = $importFeed->toArray();

        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            $supportedLanguages = get_supported_languages();
            if (!empty($supportedLanguages)) {
                $this->supported_languages = $supportedLanguages;
            }
        }
    }

}
