<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class DropdownMapping extends Component
{
    public $importFeedId;
    public $mapKey;
    public $dropdowns = [];
    public $selectField = false;
    public $mediaUrlsSeperator = false;
    public $categorySeparator = false;
    public $tagsSeperator = false;
    public $customContentData = false;

    public function updatedSelectField($field)
    {
        $this->updateFeedMapKeys();
    }

    public function updateFeedMapKeys()
    {
        $findFeed = ImportFeed::where('id', $this->importFeedId)->first();
        if ($findFeed) {

            // Main mapped tags
            $mappedTags = $findFeed->mapped_tags;
            $mappedTags[$this->mapKey] = $this->selectField;
            $findFeed->mapped_tags = $mappedTags;

            // Media Urls Separators
            $mediaUrlSeparators = $findFeed->media_url_separators;
            $mediaUrlSeparators[$this->mapKey] = $this->selectField;
            $findFeed->media_url_separators = $mediaUrlSeparators;

            // Category Separators
            $categorySeparators = $findFeed->category_separators;
            $categorySeparators[$this->mapKey] = $this->selectField;
            $findFeed->media_url_separators = $categorySeparators;



            $findFeed->save();
        }
    }

    public function render()
    {
        $findFeed = ImportFeed::where('id', $this->importFeedId)->first();
        if ($findFeed) {
            if (isset($findFeed->mapped_tags[$this->mapKey])) {
                $this->selectField = $findFeed->mapped_tags[$this->mapKey];
            }
        }

        return view('import_export_tool::admin.dropdown-mapping.dropdown');
    }
}
