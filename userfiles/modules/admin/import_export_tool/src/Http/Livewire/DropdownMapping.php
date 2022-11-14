<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class DropdownMapping extends Component
{
    public $importFeedId;
    public $mapKey;
    public $value;
    public $dropdowns = [];
    public $selectField = false;
    public $mediaUrlSeparator = false;
    public $categorySeparator = false;
    public $categoryIdSeparator = false;
    public $tagsSeparator = false;
    public $customContentData = false;
    public $categoryAddType = false;

    public function mount()
    {
        if (!empty($this->dropdowns)) {
            foreach ($this->dropdowns as $groupName=>$groupItems) {
                foreach($groupItems as $groupItem) {
                    if ($groupItem['selected']) {

                        $this->selectField = $groupItem['value'];

                        if ($groupItem['value'] == 'media_urls') {
                            foreach (ItemMapReader::$categorySeparators as $separator) {
                                if (strpos($this->value, $separator) !== false) {
                                    $this->mediaUrlSeparator = $separator;
                                    break;
                                }
                            }
                        }

                        if ($groupItem['value'] == 'categories') {
                            foreach (ItemMapReader::$categorySeparators as $separator) {
                                if (strpos($this->value, $separator) !== false) {
                                    $this->categorySeparator = $separator;
                                    break;
                                }
                            }
                        }

                        if ($groupItem['value'] == 'tags') {
                            foreach (ItemMapReader::$categorySeparators as $separator) {
                                if (strpos($this->value, $separator) !== false) {
                                    $this->tagsSeparator = $separator;
                                    break;
                                }
                            }
                        }

                        if ($groupItem['value'] == 'category_ids') {
                            foreach (ItemMapReader::$categorySeparators as $separator) {
                                if (strpos($this->value, $separator) !== false) {
                                    $this->categoryIdSeparator = $separator;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function updatedCategoryAddType()
    {
        $this->updateFeedMapKeys();
    }

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

            // Category Adding Type
            if ($this->categoryAddType) {
                $categoryAddTypes = $findFeed->category_add_types;
                $categoryAddTypes[$this->mapKey] = $this->categoryAddType;
                $findFeed->category_add_types = $categoryAddTypes;
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
            if (isset($findFeed->category_add_types[$this->mapKey])) {
                $this->categoryAddType = $findFeed->category_add_types[$this->mapKey];
            }
            if (isset($findFeed->media_url_separators[$this->mapKey])) {
                $this->mediaUrlSeparator = $findFeed->media_url_separators[$this->mapKey];
            }
            if (isset($findFeed->tags_separators[$this->mapKey])) {
                $this->tagsSeparator = $findFeed->tags_separators[$this->mapKey];
            }
            if (isset($findFeed->category_ids_separators[$this->mapKey])) {
                $this->categoryIdSeparator = $findFeed->category_ids_separators[$this->mapKey];
            }
            if (isset($findFeed->category_separators[$this->mapKey])) {
                $this->categorySeparator = $findFeed->category_separators[$this->mapKey];
            }
            if (isset($findFeed->custom_content_data_fields[$this->mapKey])) {
                $this->customContentData = $findFeed->custom_content_data_fields[$this->mapKey];
            }
        }

        return view('import_export_tool::admin.dropdown-mapping.dropdown');
    }
}
