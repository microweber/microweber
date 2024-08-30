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
        $failedIds = array();

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

        DB::beginTransaction();
        foreach($getItems['items'] as $item) {

            if ($multilanguageEnabled) {
                if (!isset($item['title'])) {
                    if (isset($item['multilanguage']['title'][$defaultLang])) {
                        $item['title'] = $item['multilanguage']['title'][$defaultLang];
                    }
                }
                if (!isset($item['description'])) {
                    if (isset($item['multilanguage']['description'][$defaultLang])) {
                        $item['description'] = $item['multilanguage']['description'][$defaultLang];
                    }
                }
                if (!isset($item['content_meta_title'])) {
                    if (isset($item['multilanguage']['content_meta_title'][$defaultLang])) {
                        $item['content_meta_title'] = $item['multilanguage']['content_meta_title'][$defaultLang];
                    }
                }
                if (!isset($item['content_meta_keywords'])) {
                    if (isset($item['multilanguage']['content_meta_keywords'][$defaultLang])) {
                        $item['content_meta_keywords'] = $item['multilanguage']['content_meta_keywords'][$defaultLang];
                    }
                }
                if (!isset($item['slug'])) {
                    if (isset($item['multilanguage']['slug'][$defaultLang])) {
                        $item['slug'] = $item['multilanguage']['slug'][$defaultLang];
                    }
                }

                if (!isset($item['content_fields'])) {
                    if (isset($item['multilanguage']['content_fields']) && !empty($item['multilanguage']['content_fields'])) {
                        foreach ($item['multilanguage']['content_fields'] as $contentFieldKey=>$contentFieldLocale) {
                            if (isset($contentFieldLocale[$defaultLang])) {
                                $item['content_fields'][$contentFieldKey] = $contentFieldLocale[$defaultLang];
                            }
                        }
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

                    try {
                        $findCategoryById = Category::where('id', $updateCategoryId)->first();
                        if (isset($item['media_urls'])) {
                            unset($item['media_urls']);
                        }
                        $findCategoryById->fill($item);
                        $findCategoryById->save();

                        $savedIds[] = $findCategoryById->id;
                    } catch (\Exception $e) {
                        $newItemId = 0;
                        if (isset($item['id'])) {
                            $newItemId = $item['id'];
                        }
                        $failedIds[] = [
                            'id'=>  $newItemId,
                            'message'=>$e->getMessage()
                        ];
                    }
                }

                if ($insertNewCategory) {
                    try {
                        $newCategory = new Category();
                        if (isset($item['id'])) {
                            $newCategory->id = $item['id'];
                        }
                        $newCategory->fill($item);
                        $newCategory->save();

                        $savedIds[] = $newCategory->id;
                    } catch (\Exception $e) {
                        $newItemId = 0;
                        if (isset($item['id'])) {
                            $newItemId = $item['id'];
                        }
                        $failedIds[] = [
                            'id'=>  $newItemId,
                            'message'=>$e->getMessage()
                        ];
                    }
                }

            } else {

                $item['parent'] = $this->importFeed->parent_page;

                $updateProductId = 0;
                $insertNewProduct = true;

                $findItemType = 'id';

                if ($this->importFeed->primary_key == 'title') {
                    $findItemType = 'title';
                }

                if (strpos($this->importFeed->primary_key,'content_data.') !== false) {
                    $findItemType = 'contentData';
                }

                if ($findItemType == 'contentData') {
                    $primaryKeyUndot = explode('.', $this->importFeed->primary_key);

                    if (!isset($primaryKeyUndot[1])) {
                        return ['error'=>'Please map the content data key from feed or change primary key to another identify method.'];
                    }

                    if (!isset($item['content_data'][$primaryKeyUndot[1]])) {
                        return ['error'=>'Please map the '.$primaryKeyUndot[1].' from feed or change primary key to another identify method.'];
                    }

                    $contentDataPrimaryKeyValue = $item['content_data'][$primaryKeyUndot[1]];
                    $findProduct = Product::whereContentData([$primaryKeyUndot[1], $contentDataPrimaryKeyValue])->first();
                    if ($findProduct) {
                        // Update product
                        $insertNewProduct = false;
                        $updateProductId = $findProduct->id;
                    }
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

                if ($findItemType == 'title') {
                    if (!isset($item['title'])) {
                        return ['error'=>'Please map the title from feed or change primary key to another identify method.'];
                    }
                    $findProduct = Product::where('title', $item['title'])->first();
                    if ($findProduct) {
                        // Update product
                        $insertNewProduct = false;
                        $updateProductId = $findProduct->id;
                    }
                }

                if ($updateProductId > 0) {

                    if (isset($item['categories'])) {
                        foreach ($item['categories'] as $category) {
                            $findCategory = Category::where('title', $category['name'])->first();
                            if (!$findCategory) {
                                $findCategory = new Category();
                                $findCategory->title = $category['name'];
                                $findCategory->rel_type = 'content';
                                $findCategory->rel_id = $this->importFeed->parent_page;
                                $findCategory->save();
                            }
                            $item['category_ids'][] = $findCategory->id;
                            if (isset($category['childs'])) {
                                foreach ($category['childs'] as $categoryChild) {
                                    $findCategoryChild = Category::where('title', $categoryChild['name'])->first();
                                    if (!$findCategoryChild) {
                                        $findCategoryChild = new Category();
                                        $findCategoryChild->parent_id = $findCategory->id;
                                        $findCategoryChild->title = $categoryChild['name'];
                                        $findCategoryChild->save();
                                    }
                                    $item['category_ids'][] = $findCategoryChild->id;
                                }
                            }
                        }
                    }

                    try {
                        $findProductById = Product::where('id', $updateProductId)->first();
                        $findProductById->fill($item);
                        $findProductById->save();
                    } catch (\Exception $e) {
                        $failedIds[] = [
                          'id'=>  $updateProductId,
                            'message'=>$e->getMessage()
                        ];
                    }

                    $savedIds[] = $findProductById->id;
                }

                if ($insertNewProduct) {

                    try {
                        $newProduct = new Product();
                        if (isset($item['id'])) {
                            $newProduct->id = $item['id'];
                        }
                        $newProduct->fill($item);
                        $newProduct->save();
                    } catch (\Exception $e) {
                        $newItemId = 0;
                        if (isset($item['id'])) {
                            $newItemId = $item['id'];
                        }
                        $failedIds[] = [
                            'id'=>  $newItemId,
                            'message'=>$e->getMessage()
                        ];
                    }
                    $savedIds[] = $newProduct->id;
                }

            }

        }

        DB::commit();

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
