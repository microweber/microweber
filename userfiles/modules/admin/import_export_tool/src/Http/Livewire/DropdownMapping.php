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
    public $mediaUrlSeparator = false;
    public $categorySeparator = false;
    public $categoryIdSeparator = false;
    public $tagsSeparator = false;
    public $customContentData = false;

    public function updatedCustomContentData($field)
    {
        $field = str_slug($field);
        $field = str_replace('-', '_', $field);
        $field = str_replace(' ', '_', $field);

        $this->customContentData = $field;
        $this->updateFeedMapKeys();
    }

    public function updatedSelectField()
    {
        $this->updateFeedMapKeys();
    }

    public function updatedMediaUrlSeparator()
    {
        $this->updateFeedMapKeys();
    }

    public function updatedCategorySeparator()
    {
        $this->updateFeedMapKeys();
    }

    public function updatedCategoryIdSeparator()
    {
        $this->updateFeedMapKeys();
    }

    public function updatedTagsSeparator()
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

            // Custom content data
            if ($this->customContentData) {
                $customContentDataFields = $findFeed->custom_content_data_fields;
                $customContentDataFields[$this->mapKey] = $this->customContentData;
                $findFeed->custom_content_data_fields = $customContentDataFields;
            }

              // Media Urls Separators
            if ($this->mediaUrlSeparator) {
                $mediaUrlSeparators = $findFeed->media_url_separators;
                $mediaUrlSeparators[$this->mapKey] = $this->mediaUrlSeparator;
                $findFeed->media_url_separators = $mediaUrlSeparators;
            }

            // Category Separators
            if ($this->categorySeparator) {
                $categorySeparators = $findFeed->category_separators;
                $categorySeparators[$this->mapKey] = $this->categorySeparator;
                $findFeed->category_separators = $categorySeparators;
            }

            // Category Ids Separators
            if ($this->categoryIdSeparator) {
                $categoryIdsSeparators = $findFeed->category_ids_separators;
                $categoryIdsSeparators[$this->mapKey] = $this->categoryIdSeparator;
                $findFeed->category_ids_separators = $categoryIdsSeparators;
            }

            // Tags Separators
            if ($this->tagsSeparator) {
                $tagsSeparators = $findFeed->tags_separators;
                $tagsSeparators[$this->mapKey] = $this->tagsSeparator;
                $findFeed->tags_separators = $tagsSeparators;
            }

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
