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
        $xmlFile = base_path() . DS . $this->importFeed->source_file_realpath;

        $contentXml = file_get_contents($xmlFile);
        $newReader = new XmlToArray();
        $data = $newReader->readXml($contentXml);

        $iterratableData = Arr::get($data, $this->importFeed->content_tag);
        if (empty($iterratableData)) {
            $this->done = true;
            return false;
        }

        $mappedData = [];
        foreach ($iterratableData as $itemI => $item) {
            foreach ($this->importFeed->mapped_tags as $tagKey => $internalKey) {

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
