<?php

namespace MicroweberPackages\ContentFilter\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Content\Content;

class ContentFilterController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
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

        return view('contentFilter::index', [
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
