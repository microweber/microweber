<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Export\Formats\CsvExport;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\Export\Formats\XmlExport;
use MicroweberPackages\Modules\Admin\ImportExportTool\BuildCategoryTree;
use MicroweberPackages\Modules\Admin\ImportExportTool\BuildProductCategoryTree;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;

class ExportWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return $this->view('import_export_tool::admin.render-livewire', [
            'component'=>'import_export_tool::export_wizard'
        ]);
    }

    public function deleteFile($id)
    {
        $findExportFeed = ExportFeed::where('id', $id)->first();
        if ($findExportFeed) {
            $findExportFeed->delete();
        }

        return redirect(route('admin.import-export-tool.index-exports'));
    }

    public function file($id)
    {

        $findExportFeed = ExportFeed::where('id', $id)->first();
        if ($findExportFeed) {

            $findExportFeed->is_draft = 0;
            $findExportFeed->save();

            $exportData = [];

            if ($findExportFeed->export_type == 'products') {
                if ($findExportFeed->export_format == 'xlsx' || $findExportFeed->export_format == 'csv' || $findExportFeed->export_format == 'xml') {
                    $exportData = $this->exportProductOneLevelArray();
                }
            }

            if ($findExportFeed->export_type == 'categories') {
                if ($findExportFeed->export_format == 'xlsx' || $findExportFeed->export_format == 'csv' || $findExportFeed->export_format == 'xml') {
                    $exportData = $this->exportCategoriesOneLevelArray();
                }
            }

            if ($findExportFeed->export_type == 'posts') {
                if ($findExportFeed->export_format == 'xlsx' || $findExportFeed->export_format == 'csv' || $findExportFeed->export_format == 'xml') {
                    $exportData = $this->exportPostsOneLevelArray();
                }
            }

            if ($findExportFeed->export_type == 'pages') {
                if ($findExportFeed->export_format == 'xlsx' || $findExportFeed->export_format == 'csv' || $findExportFeed->export_format == 'xml') {
                    $exportData = $this->exportPagesOneLevelArray();
                }
            }

            if ($findExportFeed->export_format == 'csv') {

                $export = new CsvExport([$findExportFeed->export_type => $exportData]);
                $export->setOverwrite(true);

                $file = $export->start();

                return redirect($file['files'][0]['download']);
            }

            if ($findExportFeed->export_format == 'xml') {

                $export = new XmlExport([$findExportFeed->export_type => $exportData]);
                $export->setOverwrite(true);

                $file = $export->start();

                return redirect($file['files'][0]['download']);
            }

            if ($findExportFeed->export_format == 'xlsx') {

                $export = new XlsxExport([$findExportFeed->export_type => $exportData]);
                $export->setOverwrite(true);

                $file = $export->start();

                return redirect($file['files'][0]['download']);
            }
        }
    }

    public function exportCategoriesOneLevelArray()
    {
        $exportData = [];

        $getAllCategories = Category::all();
        if ($getAllCategories->count() > 0) {
            foreach ($getAllCategories as $category) {
                $appendCategory = [];

                $appendCategory['id'] = $category->id;
                $appendCategory['parent_id'] = $category->parent_id;
                $appendCategory['title'] = $category->title;
                $appendCategory['url'] = $category->url;
                $appendCategory['is_deleted'] = $category->is_deleted;
                $appendCategory['is_hidden'] = $category->is_hidden;
                $appendCategory['category_meta_title'] = $category->category_meta_title;
                $appendCategory['category_meta_keywords'] = $category->category_meta_keywords;
                $appendCategory['category_meta_description'] = $category->category_meta_description;

                if (isset($category->multilanguage)) {
                    foreach ($category->multilanguage as $locale=>$mlFields) {
                        foreach ($mlFields as $mlFieldKey=>$mlFieldValue) {
                            $appendCategory[$mlFieldKey.'_'.strtolower($locale)] = $mlFieldValue;
                        }
                    }
                }

                $exportData[] = $appendCategory;
            }
        }

        return $exportData;
    }

    public function exportCategoriesPlainOneLevelArray()
    {
        $exportData = [];
        $categoryTreeItems = app()->category_repository->tree();
        if (!empty($categoryTreeItems)) {
            $categoriesPlain = [];
            foreach ($categoryTreeItems as $categoryTreeItem) {
                $tree = new BuildCategoryTree($categoryTreeItem);
                $getTree = $tree->get();
                if (isset($getTree['plain'])) {
                    $categoriesPlain[] = $getTree['plain'];
                }
            }
            $exportData['categories'] = $categoriesPlain;
        }

        return $exportData;
    }

    public function exportPostsOneLevelArray()
    {
        $exportData = [];
        $categoryTreeItems = app()->category_repository->tree();
        $getAllPosts = Post::all();

        foreach ($getAllPosts as $post) {

            $appendPost = [];
            $appendPost['id'] = $post->id;
            $appendPost['parent_id'] = $post->parent;

            if (isset($post->multilanguage)) {
                foreach ($post->multilanguage as $locale=>$mlFields) {
                    foreach ($mlFields as $mlFieldKey=>$mlFieldValue) {
                        $appendPost[$mlFieldKey.'_'.strtolower($locale)] = $mlFieldValue;
                    }
                }
            }  else {
                $appendPost['title'] = $post->title;
                $appendPost['url'] = $post->url;
                $appendPost['content_body'] = $post->content_body;
                $appendPost['content_meta_title'] = $post->content_meta_title;
                $appendPost['content_meta_keywords'] = $post->content_meta_keywords;
            }

            $contentData = $post->contentData()->get();
            if ($contentData->count() > 0) {
                foreach ($contentData as $contentDataItem) {
                    $appendPost[$contentDataItem->field_name] = $contentDataItem->field_value;
                }
            }

            $contentField = $post->contentField()->get();
            if ($contentField->count() > 0) {
                foreach ($contentField as $contentFieldItem) {
                    if (isset($contentFieldItem->multilanguage)) {
                        foreach ($contentFieldItem->multilanguage as $locale=>$mlFields) {
                            $appendPost[$contentFieldItem->field.'_'.strtolower($locale)] = $mlFields['value'];
                        }
                    } else {
                        $appendPost[$contentFieldItem->field] = $contentFieldItem->value;
                    }
                }
            }

            $appendPost['is_active'] = $post->is_active;

            $tags = $post->tags->toArray();
            if (!empty($tags)) {
                $tagsPlainText = [];
                foreach ($tags as $tag) {
                    $tagsPlainText[] = $tag['tag_name'];
                }
                $appendPost['tags'] = implode(', ',$tagsPlainText);
            }

            $postCategories = $post->categories->toArray();

            if (!empty($postCategories)) {

                $postCategoryIds = [];
                foreach ($postCategories as $postCategory) {
                    $postCategoryIds[] = $postCategory['category']['id'];
                }

                $appendPost['category_ids'] = implode(',', $postCategoryIds);

                $tree = new BuildProductCategoryTree($categoryTreeItems, $postCategories);
                $getTree = $tree->get();
                if (!empty($getTree)) {
                    $treeI = 0;
                    foreach ($getTree as $treeItem) {
                        if (isset($treeItem['plain'])) {
                            $appendPost['category_' . $treeI] = $treeItem['plain'];
                            $treeI++;
                        }
                    }
                }
            }

            $exportData[] = $appendPost;
        }

        return $exportData;
    }

    public function exportPagesOneLevelArray()
    {
        $exportData = [];
        $getAllPages = Page::all();

        foreach ($getAllPages as $page) {

            $appendPage = [];
            $appendPage['id'] = $page->id;
            $appendPage['parent_id'] = $page->parent;

            if (isset($post->multilanguage)) {
                foreach ($post->multilanguage as $locale=>$mlFields) {
                    foreach ($mlFields as $mlFieldKey=>$mlFieldValue) {
                        $appendPage[$mlFieldKey.'_'.strtolower($locale)] = $mlFieldValue;
                    }
                }
            }  else {
                $appendPage['title'] = $page->title;
                $appendPage['url'] = $page->url;
                $appendPage['content_body'] = $page->content_body;
                $appendPage['content_meta_title'] = $page->content_meta_title;
                $appendPage['content_meta_keywords'] = $page->content_meta_keywords;
            }

            $contentData = $page->contentData()->get();
            if ($contentData->count() > 0) {
                foreach ($contentData as $contentDataItem) {
                    $appendPage[$contentDataItem->field_name] = $contentDataItem->field_value;
                }
            }

            $contentField = $page->contentField()->get();
            if ($contentField->count() > 0) {
                foreach ($contentField as $contentFieldItem) {
                    if (isset($contentFieldItem->multilanguage)) {
                        foreach ($contentFieldItem->multilanguage as $locale=>$mlFields) {
                            $appendPage[$contentFieldItem->field.'_'.strtolower($locale)] = $mlFields['value'];
                        }
                    } else {
                        $appendPage[$contentFieldItem->field] = $contentFieldItem->value;
                    }
                }
            }

            $appendPage['is_active'] = $page->is_active;

            $tags = $page->tags->toArray();
            if (!empty($tags)) {
                $tagsPlainText = [];
                foreach ($tags as $tag) {
                    $tagsPlainText[] = $tag['tag_name'];
                }
                $appendPage['tags'] = implode(', ',$tagsPlainText);
            }

            $exportData[] = $appendPage;
        }

        return $exportData;
    }

    public function exportProductOneLevelArray()
    {
        $exportData = [];
        $categoryTreeItems = app()->category_repository->tree();
        $getAllProducts = Product::all();

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

            $getProductMedia = $product->media()->get();
            if ($getProductMedia->count() > 0) {
                $imageUrls = [];
                foreach ($getProductMedia as $media) {
                    $imageUrls[] = $media->filename;
                }
                $appendProduct['media_urls'] = implode(',', $imageUrls);
            }

            $exportData[] = $appendProduct;
        }

        return $exportData;
    }
}
