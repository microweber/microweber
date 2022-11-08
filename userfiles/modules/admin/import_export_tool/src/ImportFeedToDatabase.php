<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Product\Models\Product;

class ImportFeedToDatabase
{
    public $importFeed;
    public $importFeedId;
    public $batchStep = 0;
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

    public function setBatchStep($step)
    {
        $this->batchStep = $step;
    }

    public function getItems()
    {
        if ($this->batchImporting) {
            $totalItemsForSave = sizeof($this->importFeed->mapped_content);
            $totalItemsForBatch = (int) ceil($totalItemsForSave / $this->importFeed->split_to_parts);
            if ($totalItemsForBatch > 0) {
                $itemsBatch = array_chunk($this->importFeed->mapped_content, $totalItemsForBatch);
                if (isset($itemsBatch[$this->batchStep])) {
                    return ['items' => $itemsBatch[$this->batchStep], 'finished' => false];
                }
            }
            return ['items'=> [], 'finished'=>true];
        } else {
            return ['items'=>$this->importFeed->mapped_content,'finished'=>true];
        }
    }

    public function start()
    {
        $multilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        $defaultLang = default_lang();
        $savedIds = array();

        $getItems = $this->getItems();

        $markImportStart = true;
        if ($this->batchImporting) {
            if ($this->batchStep > 0) {
                $markImportStart = false;
            }
        }

        if ($markImportStart) {
            $this->importFeed->total_running = 1;
            $this->importFeed->last_import_start = Carbon::now();
            $this->importFeed->save();
        }

        //DB::beginTransaction();
        foreach($getItems['items'] as $item) {

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

               // dd($item);

                $updateCategoryId = 0;
                $insertNewCategory = true;
                $findCategory = Category::where('id', $item['id'])->first();
                if ($findCategory) {
                    // Update category
                    $insertNewCategory = false;
                    $updateCategoryId = $findCategory->id;
                }

                if ($updateCategoryId > 0) {

                    $findCategoryById = Category::where('id', $updateCategoryId)->first();
                    if (isset($item['media_urls'])) {
                        unset($item['media_urls']);
                    }
                    $findCategoryById->fill($item);
                    $findCategoryById->save();

                    $savedIds[] = $findCategoryById->id;
                }

                if ($insertNewCategory) {
                    $newCategory = new Category();
                    if (isset($item['id'])) {
                        $newCategory->id = $item['id'];
                    }
                    $newCategory->fill($item);
                    $newCategory->save();

                    $savedIds[] = $newCategory->id;
                }


            } else {
                $item['parent'] = $this->importFeed->parent_page;

                // dd($item);

                $updateProductId = 0;
                $insertNewProduct = true;

                $findItemType = 'id';
                if (strpos($this->importFeed->primary_key, 'custom_content_data') !== false) {
                    $primaryKeyUndot = explode('.', $this->importFeed->primary_key);
                    if (isset($primaryKeyUndot[1])) {
                        if (isset($item[$primaryKeyUndot[0]][$primaryKeyUndot[1]])) {
                            $findItemType = 'findByCustomContentData';
                            $contentDataPrimaryKeyValue = $item[$primaryKeyUndot[0]][$primaryKeyUndot[1]];
                            $findProduct = Product::whereContentData([$primaryKeyUndot[1], $contentDataPrimaryKeyValue])->first();
                            if ($findProduct) {
                                // Update product
                                $insertNewProduct = false;
                                $updateProductId = $findProduct->id;
                            }
                        }
                    }
                }

                if (isset($item['custom_content_data'])  && !empty($item['custom_content_data'])  && is_array($item['custom_content_data'])) {
                    foreach ($item['custom_content_data'] as $customContentDataKey=>$customContentDataValue) {
                        $item['content_data'][$customContentDataKey] = $customContentDataValue;
                    }
                    unset($item['custom_content_data']);
                }

                if ($findItemType == 'id') {
                    if (!isset($item['id'])) {
                        return ['error'=>'Please map the id from feed or change primary key to another identify method.'];
                    }
                    $findProduct = Product::where('id', $item['id'])->first();
                    if ($findProduct) {
                        // Update product
                        $insertNewProduct = false;
                        $updateProductId = $findProduct->id;
                    }
                }

                if ($updateProductId > 0) {

                    $findProductById = Product::where('id', $updateProductId)->first();

                    if (isset($item['media_urls'])) {
                        unset($item['media_urls']);
                    }

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

            //dd($item);
            //break;
        }

        //DB::commit();

        $importedContentIds = [];
        if (is_array($this->importFeed->imported_content_ids)) {
            $importedContentIds = array_merge($importedContentIds, $this->importFeed->imported_content_ids);
        }
        $importedContentIds = array_merge($importedContentIds, $savedIds);
        $importedContentIds = array_unique($importedContentIds);

        $this->importFeed->total_running = $this->batchStep;
        $this->importFeed->imported_content_ids = $importedContentIds;
        $this->importFeed->save();


        $markImportFinished = true;
        if ($this->batchImporting) {
            $markImportFinished = $getItems['finished'];
        }

        if ($markImportFinished) {
            $this->importFeed->is_draft = 0;
            $this->importFeed->total_running = 0;
            $this->importFeed->last_import_end = Carbon::now();
            $this->importFeed->save();
        }

        return ['finished'=>$markImportFinished];
    }
}
