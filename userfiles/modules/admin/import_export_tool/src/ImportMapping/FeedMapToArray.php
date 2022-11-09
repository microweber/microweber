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

                $tagKeyClear = str_replace(';', '.', $tagKey);
                $tagKeyClear = str_replace($this->importFeed->content_tag . '.', '', $tagKeyClear);

                $saveItem = Arr::get($item, $tagKeyClear);

                if (isset(ItemMapReader::$itemTypes[$internalKey])) {

                    // One tag item with category seperator
                    if ($internalKey == 'categories') {
                        if (isset($this->importFeed->category_separators[$tagKey])) {
                            $mappedData[$itemI][$internalKey] = [];
                            $categorySeparator = $this->importFeed->category_separators[$tagKey];
                            $categories = explode($categorySeparator, $saveItem);
                            if (!empty($categories)) {

                                $categories = array_map('trim', $categories);

                                if (isset($this->importFeed->category_add_types[$tagKey]) && $this->importFeed->category_add_types[$tagKey] == 'tree') {
                                    $categoriesPrepare = [];
                                    if (!empty($categories)) {
                                        foreach ($categories as $category) {
                                            $categoriesPrepare[] = [
                                                'name' => $category,
                                            ];
                                        }
                                    }
                                    $mappedData[$itemI][$internalKey] = $this->buildCategoryTreeFromFirstLevel($categoriesPrepare);
                                } else {
                                    $mappedData[$itemI][$internalKey] = $categories;
                                }
                            }
                            continue;
                        }
                    }

                    if ($internalKey == 'category_ids') {
                        if (isset($this->importFeed->category_ids_separators[$tagKey])) {
                            $mappedData[$itemI][$internalKey] = [];
                            $categoryIdsSeparator = $this->importFeed->category_ids_separators[$tagKey];
                            $categoryIds = explode($categoryIdsSeparator, $saveItem);
                            if (!empty($categoryIds)) {
                                $categoryIds = array_map('trim', $categoryIds);
                                $mappedData[$itemI][$internalKey] = $categoryIds;
                            }
                            continue;
                        }
                    }

                    if ($internalKey == 'tags') {
                        if (isset($this->importFeed->tags_separators[$tagKey])) {
                            $mappedData[$itemI][$internalKey] = [];
                            $tagSeperator = $this->importFeed->tags_separators[$tagKey];
                            $tags = explode($tagSeperator, $saveItem);
                            if (!empty($tags)) {
                                $tags = array_map('trim', $tags);
                                $mappedData[$itemI][$internalKey] = $tags;
                            }
                            continue;
                        }
                    }

                    if ($internalKey == 'media_urls') {
                        if (isset($this->importFeed->media_url_separators[$tagKey])) {
                            $mappedData[$itemI][$internalKey] = [];
                            $mediaUrlSeperator = $this->importFeed->media_url_separators[$tagKey];
                            $mediaUrls = explode($mediaUrlSeperator, $saveItem);
                            if (!empty($mediaUrls)) {
                                $mediaUrls = array_map('trim', $mediaUrls);
                                $mappedData[$itemI][$internalKey] = $mediaUrls;
                            }
                            continue;
                        }
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

    public function buildCategoryTreeFromFirstLevel($array, $i = 0)
    {
        $next = $i + 1;
        if (isset($array[$next])) {
            return array('0' => array('name' => $array[$i]['name'], 'childs' => $this->buildCategoryTreeFromFirstLevel($array, $next)));
        } else {
            return array('0' => array('name' => $array[$i]['name'], 'childs' => []));
        }
    }

}
