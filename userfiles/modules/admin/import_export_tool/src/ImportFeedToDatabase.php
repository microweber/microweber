<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Product\Models\Product;

class ImportFeedToDatabase
{
    public $importFeedId;
    public $batchImporting = false;

    public function setImportFeedId($id)
    {
        $this->importFeedId = $id;
    }

    public function setBatchImporting($import)
    {
        $this->batchImporting = $import;
    }

    public function getItems()
    {
        /*if ($this->batchImporting) {
            $totalItemsForSave = sizeof($this->import_feed->mapped_content);
            $totalItemsForBatch = (int) ceil($totalItemsForSave / $this->import_log['total_steps']);
            $itemsBatch = array_chunk($this->import_feed->mapped_content, $totalItemsForBatch);
            return $itemsBatch[$selectBatch];
        }*/
    }

    public function start()
    {
        \Config::set('microweber.disable_model_cache', 1);
        \Config::set('cache.driver', 'array');
        app('cache')->setDefaultDriver('array');

        $multilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        $defaultLang = default_lang();
        $savedIds = array();

        //DB::beginTransaction();
        foreach($this->items as $item) {

            if ($multilanguageEnabled) {
                if (!isset($item['title'])) {
                    if (isset($item['multilanguage']['title'][$defaultLang])) {
                        $item['title'] = $item['multilanguage']['title'][$defaultLang];
                    }
                    if (isset($item['multilanguage']['description'][$defaultLang])) {
                        $item['description'] = $item['multilanguage']['description'][$defaultLang];
                    }
                    if (isset($item['multilanguage']['content_meta_title'][$defaultLang])) {
                        $item['content_meta_title'] = $item['multilanguage']['content_meta_title'][$defaultLang];
                    }
                    if (isset($item['multilanguage']['content_meta_keywords'][$defaultLang])) {
                        $item['content_meta_keywords'] = $item['multilanguage']['content_meta_keywords'][$defaultLang];
                    }
                    if (isset($item['multilanguage']['slug'][$defaultLang])) {
                        $item['slug'] = $item['multilanguage']['slug'][$defaultLang];
                    }
                }
            }

            if ($this->import_feed->import_to == 'categories') {

                $item['rel_id'] = $this->import_feed->parent_page;
                $item['rel_type'] = 'content';

                if (isset($item['parent_id'])) {
                    $findParentCategory = Category::where('id', $item['parent_id'])->first();
                    if (!$findParentCategory) {
                        $item['parent_id'] = 0;
                    }
                }

                $findCategory = Category::where('id', $item['id'])->first();
                if ($findCategory) {
                    $findCategory->update($item);
                } else {
                    $categoryCreate = Category::create($item);
                }

            } else {
                $item['parent'] = $this->import_feed->parent_page;

                if (!isset($item['id'])) {
                    $item['id'] = 0;
                }

                $findProduct = Product::where('id', $item['id'])->first();
                if ($findProduct) {
                    $findProduct->update($item);
                } else {
                    $productCreate = \MicroweberPackages\Product\Models\Product::create($item);
                }
            }

        }
        //DB::commit();

        if (empty($this->import_feed->imported_content_ids)) {
            $this->import_feed->imported_content_ids = [];
        }

        $importedContentIds = [];
        $importedContentIds = array_merge($importedContentIds,$this->import_feed->imported_content_ids);
        $importedContentIds = array_merge($importedContentIds,$savedIds);
        $importedContentIds = array_unique($importedContentIds);

        $this->import_feed->total_running = $this->import_log['current_step'];
        $this->import_feed->imported_content_ids = $importedContentIds;
        $this->import_feed->save();

    }
}
