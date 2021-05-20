<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Post\Models\Post;

class BlogController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $moduleId = $request->get('id');
        $contentFromId = get_option('content_from_id', $moduleId);
      //  $filteringTheResults = get_option('filtering_the_results', $moduleId);
       // $limitTheResults = get_option('limit_the_results', $moduleId);
       // $sortTheResults = get_option('sort_the_results', $moduleId);

        $postQuery = Post::query();
        $postQuery->where('parent', $contentFromId);

        $search = $request->get('search');
        if (!empty($search)) {
            $postQuery->whereLike(['title'], $search);
        }

        $postResults = $postQuery->frontendFilter();

        return view('blog::index', [
            'posts'=>$postResults,
        ]);
    }

    public function maiko() {

        $pageId = $request->get('content-id');
        $enableSort = $request->get('enable_sort',1);
        $enableLimit = $request->get('enable_limit',1);
        $pageData = $request->get('page',[]);

        if (isset($pageData[$pageId])) {
            $pageData = $pageData[$pageId];
        }

        $orderBy = '';
        if (isset($pageData['orderBy'])) {
            $orderBy = $pageData['orderBy'];
        }
        $limit = '';
        if (isset($pageData['limit'])) {
            $limit = $pageData['limit'];
        }
        $customFields= [];
        if (isset($pageData['customFields'])) {
            $customFields = $pageData['customFields'];
        }

        $filters = [];
        $queryContent = Content::query();

        if ($pageId > 0) {
            $queryContent->where('parent', $pageId);
        }

        $getContents = $queryContent->get();

        if (!empty($getContents)) {
            foreach ($getContents as $content) {

                $productCustomFields = $content->customField()->with('fieldValue')->get();
                foreach ($productCustomFields as $productCustomField) {
                    $customFieldValues = $productCustomField->fieldValue()->get();

                    if (empty($customFieldValues)) {
                        continue;
                    }

                    $filterOptions = [];
                    foreach ($customFieldValues as $customFieldValue) {
                        $filterOptions[] = [
                            'id'=>$customFieldValue->id,
                            'value'=>$customFieldValue->value,
                        ];
                    }
                    $filters[$productCustomField->name_key] = [
                        'type'=>$productCustomField->type,
                        'name'=>$productCustomField->name,
                        'options'=>$filterOptions
                    ];
                }
            }
        }

        return view('blog::index', [
            'currencySymbol'=>mw()->shop_manager->currency_symbol(),
            'pageId'=>$pageId,
            'enableSort'=>$enableSort,
            'enableLimit'=>$enableLimit,
            'filters'=>$filters,
            'orderBy'=>$orderBy,
            'customFields'=>$customFields,
            'limit'=>$limit,
        ]);
    }

}
