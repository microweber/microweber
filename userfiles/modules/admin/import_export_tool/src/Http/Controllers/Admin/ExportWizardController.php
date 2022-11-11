<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Export\Formats\XlsxExport;
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


                $categoryTree = app()->category_repository->tree();

                dd($categoryTree);


                $getAllProducts = Product::all();
                if ($findExportFeed->export_format == 'xlsx') {
                    $firstLevelArray = [];
                    foreach ($getAllProducts as $product) {

                       // $categoryTree = app()->content_repository->getCategories($product->id);

                        dd($product->getCategoriesTree());

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

                        $firstLevelArray[] = $appendProduct;
                    }


                    dd($firstLevelArray);

                    $export = new XlsxExport(['products'=>$firstLevelArray]);
                    $file = $export->start();

                    return redirect($file['files'][0]['download']);

                }
            }
        }
    }
}
