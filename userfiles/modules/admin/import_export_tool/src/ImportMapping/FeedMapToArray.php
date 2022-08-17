<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping;

use Illuminate\Support\Arr;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class FeedMapToArray
{
    public $importFeed = false;

    public function setImportFeedId($id)
    {
        $this->importFeed = ImportFeed::where('id',$id)->first();
    }

    public function toArray()
    {
        $repeatableData = Arr::get($this->importFeed->source_content, $this->importFeed->content_tag);
        if (empty($repeatableData)) {
            $this->done = true;
            return false;
        }

        $mappedData = [];
        foreach ($repeatableData as $itemI => $item) {

            $repeatableMappedTags = [];
            $mappedTags = $this->importFeed->mapped_tags;

            if (isset($mappedTags['_repeatable_'])) {
                $repeatableMappedTags = $mappedTags['_repeatable_'];
                unset($mappedTags['_repeatable_']);
            }

            if (!empty($repeatableMappedTags)) {
                foreach ($repeatableMappedTags as $tagKey=>$internalKey) {
                    $tagKey = str_replace(';', '.', $tagKey);
                    $tagKey = str_replace($this->importFeed->content_tag . '.', '', $tagKey);

                    $saveItem = Arr::get($item, $tagKey);

                    $mappedData[$itemI][$internalKey] = $saveItem;
                }
            }

            foreach ($mappedTags as $tagKey => $internalKey) {

                if (empty($internalKey)) {
                    continue;
                }

                $tagKey = str_replace(';', '.', $tagKey);
                $tagKey = str_replace($this->importFeed->content_tag . '.', '', $tagKey);

                $saveItem = Arr::get($item, $tagKey);

                if (isset(ItemMapReader::$itemTypes[$internalKey])) {

                    // One tag item with category seperator
                    if ($internalKey == 'categories' && !empty($this->importFeed->category_separator)) {
                        $categories = explode($this->importFeed->category_separator, $saveItem);
                        $mappedData[$itemI][$internalKey] = $categories;
                        continue;
                    }

                    if (ItemMapReader::$itemTypes[$internalKey] == ItemMapReader::ITEM_TYPE_ARRAY) {
                        $mappedData[$itemI][$internalKey][] = $saveItem;
                        continue;
                    }
                }

                // Save simple item type
                $mappedData[$itemI][$internalKey] = $saveItem;

            }
        }

        $preparedData = [];
        foreach ($mappedData as $undotItem) {
            $preparedItem = Arr::undot($undotItem);
            $preparedData[] = $preparedItem;
        }

        return $preparedData;
    }

}
