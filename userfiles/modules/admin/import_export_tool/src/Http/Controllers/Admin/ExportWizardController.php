<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\Modules\Admin\ImportExportTool\BuildProductCategoryTree;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Product\Models\Product;

class ExportWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return $this->view('import_export_tool::admin.render-livewire', [
            'component'=>'import_export_tool::export_wizard'
        ]);
    }

    public function file($id)
    {

        $findExportFeed = ExportFeed::where('id', $id)->first();
        if ($findExportFeed) {
            if ($findExportFeed->export_type == 'products') {

                $categoryTreeItems = app()->category_repository->tree();

                $getAllProducts = Product::all();
                if ($findExportFeed->export_format == 'xlsx') {
                    $firstLevelArray = [];
                    foreach ($getAllProducts as $product) {

                        $appendProduct = [];
                        $appendProduct['id'] = $product->id;
                        $appendProduct['parent_id'] = $product->parent;

                        if (isset($product->multilanguage)) {
                            foreach ($product->multilanguage as $locale=>$mlFields) {
                                foreach ($mlFields as $mlFieldKey=>$mlFieldValue) {
                                    $appendProduct[$mlFieldKey.'_'.strtolower($locale)] = $mlFieldValue;
                                }
                            }
                        }  else {
                            $appendProduct['title'] = $product->title;
                            $appendProduct['url'] = $product->url;
                            $appendProduct['content_body'] = $product->content_body;
                            $appendProduct['content_meta_title'] = $product->content_meta_title;
                            $appendProduct['content_meta_keywords'] = $product->content_meta_keywords;
                        }

                        $contentData = $product->contentData()->get();
                        if ($contentData->count() > 0) {
                            foreach ($contentData as $contentDataItem) {
                                $appendProduct[$contentDataItem->field_name] = $contentDataItem->field_value;
                            }
                        }

                        $contentField = $product->contentField()->get();
                        if ($contentField->count() > 0) {
                            foreach ($contentField as $contentFieldItem) {
                                if (isset($contentFieldItem->multilanguage)) {
                                    foreach ($contentFieldItem->multilanguage as $locale=>$mlFields) {
                                        $appendProduct[$contentFieldItem->field.'_'.strtolower($locale)] = $mlFields['value'];
                                    }
                                } else {
                                    $appendProduct[$contentFieldItem->field] = $contentFieldItem->value;
                                }
                            }
                        }

                        $appendProduct['price'] = $product->price;
                        $appendProduct['special_price'] = $product->special_price;
                        $appendProduct['qty'] = $product->qty;

                        $appendProduct['in_stock'] = 0;
                        if ($product->in_stock) {
                            $appendProduct['in_stock'] = 1;
                        }

                        $appendProduct['is_active'] = $product->is_active;

                        $tags = $product->tags->toArray();
                        if (!empty($tags)) {
                            $tagsPlainText = [];
                            foreach ($tags as $tag) {
                                $tagsPlainText[] = $tag['tag_name'];
                            }
                            $appendProduct['tags'] = implode(', ',$tagsPlainText);
                        }

                        $productCategories = $product->categories->toArray();

                        if (!empty($productCategories)) {

                            $productCategoryIds = [];
                            foreach ($productCategories as $productCategory) {
                                $productCategoryIds[] = $productCategory['category']['id'];
                            }

                            $appendProduct['category_ids'] = implode(',', $productCategoryIds);

                            $tree = new BuildProductCategoryTree($categoryTreeItems, $productCategories);
                            $getTree = $tree->get();
                            if (!empty($getTree)) {
                                $treeI = 0;
                                foreach ($getTree as $treeItem) {
                                    if (isset($treeItem['plain'])) {
                                        $appendProduct['category_' . $treeI] = $treeItem['plain'];
                                        $treeI++;
                                    }
                                }
                            }
                        }

                        $firstLevelArray[] = $appendProduct;
                    }
                    
                    $export = new XlsxExport(['products'=>$firstLevelArray]);
                    $file = $export->start();

                    return redirect($file['files'][0]['download']);

                }
            }
        }
    }
}
