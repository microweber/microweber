<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Product\Models\Product;

class ImportFeedToDatabase
{
    public $importFeed;
    public $importFeedId;
    public $batchImporting = false;

    public function setImportFeedId($id)
    {
        $this->importFeedId = $id;

        $findImportFeed = ImportFeed::where('id', $this->importFeedId)->first();
        if (!$findImportFeed) {
            throw new \Exception('Feed not found.');
        }

        $this->importFeed = $findImportFeed;
    }

    public function setBatchImporting($import)
    {
        $this->batchImporting = $import;
    }

    public function getItems()
    {
        if ($this->batchImporting) {
            /*$totalItemsForSave = sizeof($this->importFeed->mapped_content);
            $totalItemsForBatch = (int) ceil($totalItemsForSave / $this->import_log['total_steps']);
            $itemsBatch = array_chunk($this->importFeed->mapped_content, $totalItemsForBatch);
            return $itemsBatch[$selectBatch];*/
        } else {
            return $this->importFeed->mapped_content;
        }
    }

    public function start()
    {
        $multilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        $defaultLang = default_lang();
        $savedIds = array();

        //DB::beginTransaction();
        foreach($this->getItems() as $item) {

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

            if ($this->importFeed->import_to == 'categories') {

                $item['rel_id'] = $this->importFeed->parent_page;
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
                $item['parent'] = $this->importFeed->parent_page;

                $updateProductId = 0;
                $insertNewProduct = true;
                $findProduct = Product::where('id', $item['id'])->first();
                if ($findProduct) {
                    // Update product
                    $insertNewProduct = false;
                    $updateProductId = $findProduct->id;
                }

                if ($updateProductId > 0) {
                    $findProductById = Product::where('id', $updateProductId)->first();
                    $findProductById->fill($item);
                    $findProductById->save();

                    $savedIds[] = $findProductById->id;
                }

                if ($insertNewProduct) {
                    $newProduct = new Product();
                    if (isset($item['id'])) {
                        $newProduct->id = $item['id'];
                    }
                    $newProduct->fill($item);
                    $newProduct->save();

                    $savedIds[] = $newProduct->id;
                }

            }

            break;
        }

        dd($savedIds);


        //DB::commit();

      /*  $importedContentIds = [];
        $importedContentIds = array_merge($importedContentIds,$this->importFeed->imported_content_ids);
        $importedContentIds = array_merge($importedContentIds,$savedIds);
        $importedContentIds = array_unique($importedContentIds);

        $this->importFeed->total_running = $this->import_log['current_step'];
        $this->importFeed->imported_content_ids = $importedContentIds;
        $this->importFeed->save();*/

    }
}
